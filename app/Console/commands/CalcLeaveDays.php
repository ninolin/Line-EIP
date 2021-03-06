<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use DB;

class CalcLeaveDays extends Command
{
    // 命令名稱
    protected $signature = 'calc:leavedays';
    protected $description = '用受顧日期來更新user可休假天數';
    protected $month_days = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    protected $leave_days = [7, 10, 14, 14, 15, 15, 15, 15, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30];
    protected $MAX_DIFF_YEAR = 24;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = DB::select('select * from user where status = "T"', []);
        foreach ($users as $u) {
            $onboard_date = $u->onboard_date;
            if(!is_null($onboard_date)) {
                $leave_day = self::calc_leavedays($onboard_date, date("Y-m-d"));
                if($leave_day != 10000) {
                    $data = DB::select('select count(1) as count from eip_annual_leave where year =? and user_no =?', [date("Y"), $u->NO]);
                    foreach($data as $d) {
                        if($d->count != 0) {
                            if(DB::update('update eip_annual_leave set labor_annual_leaves =? where year =? and user_no =? ', [$leave_day, date("Y"), $u->NO]) != 1) {
                                echo 'now update data';
                            }
                        } else {
                            if(DB::insert('insert into eip_annual_leave (user_no, year, annual_leaves, labor_annual_leaves) values (?, ?, ?, ?)', [$u->NO, date("Y"), $leave_day, $leave_day]) != 1) {
                                echo 'now update data';
                            }
                        }
                    }
                }
                //12-01會發放明年的假
                if(date("m-d") == '12-01') {
                    $leave_day = self::calc_leavedays($onboard_date, (date("Y")+1)."-01-01");
                    if($leave_day != 10000) {
                        $data = DB::select('select count(1) as count from eip_annual_leave where year =? and user_no =?', [date("Y")+1, $u->NO]);
                        foreach($data as $d) {
                            if($d->count != 0) {
                                if(DB::update('update eip_annual_leave set labor_annual_leaves =? where year =? and user_no =? ', [$leave_day, date("Y")+1, $u->NO]) != 1) {
                                    echo 'now update data';
                                }
                            } else {
                                if(DB::insert('insert into eip_annual_leave (user_no, year, annual_leaves, labor_annual_leaves) values (?, ?, ?, ?)', [$u->NO, date("Y")+1, $leave_day, $leave_day]) != 1) {
                                    echo 'now update data';
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function calc_leavedays($onboard_date, $today) {
        //$today = date("Y-m-d");
        //$today = "2018-01-01";
        $date1 = explode('-',$onboard_date);
        $date2 = explode('-',$today);
        $onboard_y = $date1[0];
        $onboard_m = $date1[1];
        $onboard_d = $date1[2];
        $today_y = $date2[0];
        $today_m = $date2[1];
        $today_d = $date2[2];
        $leave_day = 10000; //若跑完成該function回傳day還是10000的話，表示不用更新user的假
        if($onboard_y == $today_y && 
            (($today_m-$onboard_m == 6) && $today_d == $onboard_d) || 
            (($today_m-$onboard_m == 7) && $today_m == '12' && $today_d == '01') || 
            (($today_m-$onboard_m == 7) && $today_m == '10' && $today_d == '01')
        ) {
            /*
             * 今天跟到職日比，剛好滿6個月且到職日在6月30日前，就會要到職前半年的假
             * 特別的是若是3/31到職，因為9月沒有31天，所以是10/1給假，5/31就是12/1給假
             */
            $leave_day = 3 - (($onboard_m-1) + ($onboard_d-1)/$this->month_days[(int)$onboard_m] )/6 * 3;
        } else if($today_m == "01" && $today_d == "01" && $today_y > $onboard_y) {
            // 每年1/1要計算當年的假
            $diff_y = $today_y - $onboard_y;
            if($diff_y > $this->MAX_DIFF_YEAR) return round(30, 1);

            $last_y_leave = 0;
            if($diff_y == 1) {
                $last_y_leave = (($onboard_m-1) + ($onboard_d-1)/$this->month_days[(int)$onboard_m] )/6 * 3;
            } else {
                $last_y_leave = (($onboard_m-1) + ($onboard_d-1)/$this->month_days[(int)$onboard_m] )/12 * $this->leave_days[$diff_y-2];
            }
            $this_y_leave = $this->leave_days[$diff_y-1] - (($onboard_m-1) + ($onboard_d-1)/$this->month_days[(int)$today_m])/12 * $this->leave_days[$diff_y-1];
            $leave_day = $last_y_leave + $this_y_leave;
        }
        return round($leave_day, 1);
    }
}