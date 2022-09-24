<?php
    require_once('../../includes/tables.php');
    require_once('../../../inc/functions.php');

    start_session('acclzsess');
    $report = new Report();
    $error_redirect = '../../';
    check_admin($error_redirect);

    if(isset($_GET['simprd']) && !empty($_GET['simprd'])){
        switch ($_GET['simprd']) {
            case 'day':
                $report->set_start_time(strtotime('today'));
                $report->set_end_time(strtotime('now'));
                $report->set_period_header('Today');
                $return = $report->generate_report();
                break;
                
            case 'yesterday':
                $report->set_start_time(strtotime('yesterday'));
                $report->set_end_time(strtotime('today'));
                $report->set_period_header('Yesterday');
                $return = $report->generate_report();
                break;
                
            case 'week':
                if(date('w', time()) == 0){
                    $report->set_start_time(strtotime('today'));
                    $report->set_end_time(strtotime('now'));
                } else {
                    $report->set_start_time(strtotime('last sunday'));
                    $report->set_end_time(strtotime('now'));
                }
                $report->set_period_header('This Week');
                $return = $report->generate_report();
                break;
                
            case 'preweek':
                if(date('w', time()) == 0){
                    $report->set_start_time(strtotime('last sunday'));
                    $report->set_end_time(strtotime('today'));
                } else {
                    $report->set_start_time(strtotime('last sunday') - 604800);
                    $report->set_end_time(strtotime('last sunday'));
                }
                $report->set_period_header('Last Week');
                $return = $report->generate_report();
                break;
                
            case 'month':
                $report->set_start_time(strtotime('midnight, first day of this month'));
                $report->set_end_time(strtotime('now'));
                $report->set_period_header('This Month');
                $return = $report->generate_report();
                break;
                
            case 'premonth':
                $report->set_start_time(strtotime('midnight, first day of last month'));
                $report->set_end_time(strtotime('midnight, first day of this month'));
                $report->set_period_header('Last Month');
                $return = $report->generate_report();
                break;
                
            case 'year':
                $report->set_start_time(strtotime("01-01-" . date('Y', strtotime('now'))));
                $report->set_end_time(strtotime('now'));
                $report->set_period_header('This Year');
                $return = $report->generate_report();
                break;
            
            case 'preyear':
                $report->set_start_time(strtotime("01-01-" . date('Y', strtotime('last year'))));
                $report->set_end_time(strtotime("01-01-" . date('Y', strtotime('now'))));
                $report->set_period_header('Last Year');
                $return = $report->generate_report();
                break;

            default:
                # code...
                break;
        }

        echo json_encode($return);
    }

    if(isset($_POST) && !empty($_POST)){
        $report->set_start_time(strtotime($_POST['start_date']));
        $report->set_end_time(strtotime("11:59:59PM " . $_POST['end_date']));
        $return_header = date("D, jS M Y", $report->get_start_time());
        $report->set_period_header(date("D, jS M Y", $report->get_start_time()) . " &#8212; " . date("D, jS M Y", $report->get_end_time()));
        $return = $report->generate_report();
        echo json_encode($return);
    }

    class Report {
        protected $start_time;
        protected $end_time;
        protected $period_header;

        public function set_start_time($time){
            $this->start_time = $time;
        }
        public function get_start_time(){
            return $this->start_time;
        }
        public function set_end_time($time){
            $this->end_time = $time;
        }
        public function get_end_time(){
            return $this->end_time;
        }
        public function set_period_header($header){
            $this->period_header = $header;
        }
        public function get_period_header(){
            return $this->period_header;
        }

        public function generate_report(){
            global $sales_table;
            global $items_table;
            global $connection;

            $report_generator_result = array();
            $report_table = "<h3 class=\"rep-table-header no-mobile\">" . $this->get_period_header() . "</h3>";
            $table = "<table class=\"items-table\" cellspacing=\"0\"><tr class=\"table-header\"><th class=\"items-cell\">Date</th><th class=\"items-cell\">Time</th><th class=\"items-cell\">Item</th><th class=\"items-cell\">Amount</th></tr>";
            $query = "SELECT * FROM {$sales_table} WHERE time BETWEEN ? AND ? ";
            $get_items = $connection->prepare($query);
            $get_items->bind_param('ii', $this->start_time, $this->end_time);
            try {
                $get_items->execute();
                $result = $get_items->get_result();
                $count = 0;
                $total = 0;
                while ($items = $result->fetch_assoc()){
                    $count++;
                    $query = "SELECT name, price FROM {$items_table} WHERE id = ?";
                    $get_item_details = $connection->prepare($query);
                    $get_item_details->bind_param('i', $items['item_id']);
                    $get_item_details->execute();
                    $result2 = $get_item_details->get_result();
                    while ($item_details = $result2->fetch_assoc()){
                        $item_name = $item_details['name'];
                        $item_price = $item_details['price'];
                        $amount_paid = $item_details['price'] * $items['quantity'];
                        $total = $total + $amount_paid;
                    }
                    $table .= "<tr><td class=\"items-cell\">". date("D, jS M Y", $items['time']) ."</td><td class=\"items-cell\">" . strftime("%I:%M %p", $items['time']) . "</td><td class=\"items-cell\">" . $item_name . "</td><td class=\"items-cell\">" . $amount_paid . "</td></tr>";
                }
                if($count > 0){
                    $table .= "<tr><td class=\"items-cell\"></td><td class=\"items-cell\"></td><td class=\"items-cell\">TOTAL</td><td class=\"items-cell\">" . $total . "</td></tr>";
                } else {
                    $table = "<div class=\"no-sales-report\">No sales made during selected period!</div>";
                }
                $report_table .= $table;
                $report_generator_result['status'] = "success";
                $report_generator_result['report'] = $report_table;
                $report_generator_result['header'] = $this->get_period_header();
            } catch (Exception $e){
                $report_generator_result['status'] = "failed";
                $report_generator_result['error'] = $e->getMessage();
            }
            return $report_generator_result;
        }

    }
?>