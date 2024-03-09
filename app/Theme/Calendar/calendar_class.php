<?php

class BFCalendarPlugin {
    function init(){
        $_apiController = new Calendar();
        $_apiController->init();
    }
}

$bfcalendar = new BFCalendarPlugin();
$bfcalendar->init();

class Calendar {

    private $naviHref = null;
    private $active_year, $active_month, $active_day;
    private $currentYear = 0;
    private $currentMonth = 0;
    private $currentDay = 0;
    private $currentDate = null;
    private $daysInMonth = 0;
    private $events = [];
    private $APIevents = [];


    public function __construct($date = null) {
        // $this->naviHref = htmlentities($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
        $this->active_year = $date != null ? date('Y', strtotime($date)) : date('Y');
        $this->active_month = $date != null ? date('m', strtotime($date)) : date('m');
        $this->active_day = $date != null ? date('d', strtotime($date)) : date('d');
        $this->_createListEvents();
    }

    function init() {
        add_action( 'wp_enqueue_scripts', [ $this, 'init_plugin' ] );

        add_action( 'wp_ajax_calendar_ajax_call', [ $this, 'calendar_ajax_call' ] );
        add_action( 'wp_ajax_nopriv_calendar_ajax_call', [ $this, 'calendar_ajax_call' ] );
        
    }

    function register_routes() {
        register_rest_route( 'bfcalendar/v1', '/event/', array(
            'methods' => 'GET',
            'callback' => 'add_event',
          ) );
    }

    public function init_plugin() {
        wp_enqueue_script( 'ajax_script', THEME_PATH .'/app/Theme/Calendar/calendar-script.js', array('jquery'), TRUE );
        wp_localize_script( 'ajax_script', 'calAjax', array(
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( "calendar_nonce" ),
            )
        );
    }

    /**
     * Change month in Calendar Class
     *
     * @return void
     */
    function calendar_ajax_call() {
        // $base = $_POST['base'];
        $month = $_POST['month'];
        $year = $_POST['y'];
    
        $calendar = new Calendar();
        $html = $calendar->show($month, $year);
    
        if ( ! check_ajax_referer( 'calendar_nonce', 'nonce' ) )
            wp_send_json_error( 'Invalid Nonce' );
    
        wp_send_json( $html );
    }


    private function _weeksInMonth($month = null, $year = null){
		if($year == null)
			$year =  date("Y", time());

		if($month == null)
			$month = date("m", time());

		// find number of weeks in this month
		$daysInMonths = $this->_daysInMonth($month, $year);
        $dayOne = date('N', strtotime($year.'-'.$month.'-01')); //monday based week 1-7
        $dayOne--; //convert for 0 based weeks
        $numOfweeks = floor(($daysInMonths - (7 - $dayOne))/7);
        if ($daysInMonths >= 31 && $dayOne === 6)
            $numOfweeks = 6;

        return $numOfweeks;
	}

    private function _daysInMonth($month = null, $year = null){
		if($year == null)
			$year =  wp_date("Y", time());

		if($month == null)
			$month = wp_date("m", time());

		return wp_date('t', strtotime($year.'-'.$month.'-01'));
	}

    private function _createListEvents() {
        // Check if the API is already called
        if (!empty($this->APIevents))
            return;

        if ( ! class_exists( 'WP_Site_Health' ) ) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
        }
        
        $request = new WP_REST_Request( 'GET', '/wp/v2/events-datetime' );
        $request->set_query_params( [ 'per_page' => 999 ] );
        $response = rest_do_request( $request );
        $server = rest_get_server();
        $this->APIevents = $server->response_to_data( $response, false );
    }

    private function _addEventToCal($calDay, $calMonth, $calYear, $currentMonth, $lastMonthDay, $ignore) {

        $calDay = strlen($calDay) == 1 ? sprintf("%02d", $calDay) : $calDay;
        $calMonth = strlen($calMonth) == 1 ? sprintf("%02d", $calMonth) : $calMonth;
        $calData = $calDay.'/'.$calMonth.'/'.$calYear;
        $event_code = '';
        $firstCurrent = '';
        $max_n = 0;
        $today = date('d/m/Y', strtotime('today'));
        $go = 'ok';    

        if( !empty($this->APIevents) ) {
            
            foreach ($this->APIevents['date'] as $data_array => $events) {

                $n = intval(count($events) -1);
                $data_array_data = date('d/m/Y', strtotime($data_array));
                $month_position = strpos($data_array, '/'.$calMonth.'/') !== false ? strpos($data_array, $calMonth) : null;

                $_toDate = date('d/m/Y', strtotime(str_replace('/', '-', $data_array)));
                $_toDay = explode('/', $_toDate);

                while ($month_position != null) {
                    $firstCurrent = $go == 'ok' ? $_toDate : $firstCurrent;
                    $go = 'ko';
                    break;
                }
        
                $next_event[$data_array]['style'] = 'display:none;';
                $next_event[$data_array]['class'] = '';
                $next_event_display = $calData == $firstCurrent ? '' : 'display:none;';
                $next_event_class = $calData == $firstCurrent ? 'active' : '';

                foreach ($events as $event) :

                    $data = $event['data'];

                    if($calYear == $_toDay[2] && $calMonth == $_toDay[1]):
                    if ($calDay == $_toDay[0]) {

                        // controllo se i giorni sono a cavallo di 2 mesi
                        if (is_string($data) && strpos((is_string($data) ? $data : chr($data)),'/') !== false ) {
                            $dayEvent = strlen($_toDay[0]) == 1 ? sprintf("%02d", $_toDay[0]) : $_toDay[0];
                            $giorno_evento = strpos($_toDay[0], '0') === 0 ? str_replace('0', '', $_toDay[0]) : $_toDay[0];
                            // definisco il mese successivo (sempre a 2 cifre)
                            $nextMonth = strlen($_toDay[1]) == 1 ? sprintf("%02d", $_toDay[1]) : $_toDay[1];
                            $calDay = strlen($calDay) == 1 ? sprintf("%02d", $calDay) : $calDay;
                            $calMonth = strlen($calMonth) == 1 ? sprintf("%02d", $calMonth) : $calMonth;

                            if (
                                date('d/m/Y', strtotime($calData)) === date('d/m/Y', strtotime((int)$_toDay[0] . '/' . (int)$_toDay[1] . '/' . $_toDay[2]))
                            ) {
                                $calNextMonthData = $dayEvent.'/'.$nextMonth.'/'.$_toDay[2];

                                $n++;

                                $event_code .= '<div class="event '.$event['cat'].' evento-'.$event['ID'].'  '.$next_event_class.'" data-id="evento-'.$event['ID'].'" event-date="'.$event['data'].'">'.$giorno_evento.'</div>';
                                
                                // aggiungo il popup visibile al click
                                $pos = ($n-1) != 0 ? '-'.(($n-1)*100).'%' : '0';
                                // $pos = $n != 0 ? '-'.($n * 100).'%' : '0';
                                // $event_code .= '<div class="dettaglio-evento" index="'.$n.'" data-pos="'.$pos.'" data-id="evento-'.$event['ID'].'" event-date="'.$_toDate.'" style="'.$next_event_display.' right:'.$pos.'">';
                                $event_code .= '<div class="dettaglio-evento" index="'.$n.'" data-pos="'.$pos.'" data-id="evento-'.$event['ID'].'" event-date="'.$event['data'].'" style="'.$next_event_display.'">';
                                    $event_code .= '<div class="inner flex">';
                                        $event_code .= '<div class="foto" style="background-image:url('.$event['featured_image'].');">';
                                            $event_code .= '<div class="cal-slide"><span class="current-slide">'.$n.'</span> / <span class="total-slide"></span></div>';
                                        $event_code .= '</div>';
                                        $event_code .= '<div class="info">';
                                            $event_code .= '<p class="title">'.$event['titolo'].'</p>';
                                            $event_code .= '<div class="pop-orari">';
                                                $event_code .= '<p><i class="bf-icon icon-calendar"></i> '.$event['data'].'</p>';
                                                $event_code .= '<p><i class="bf-icon icon-pin"></i> '.$event['location'].'</p>';
                                                $event_code .= '<p><i class="bf-icon icon-clock"></i> '.$event['orario'].'</p>';
                                            $event_code .= '</div>';
                                            $event_code .= '<a class="bf-btn primary icon-ticket" href="'.$event['ticket_link'].'">'.__('Buy tickets', 'san-carlo-theme').'</a>';
                                            $event_code .= '<a class="bf-link primary" href="'.$event['permalink'].'">'.__('Discover more', 'san-carlo-theme').'</a>';
                                        $event_code .= '</div>';
                                    $event_code .= '</div>';
                                    /* mobile */
                                    $event_code .= '<div class="info-mobile">';
                                        $event_code .= '<a class="bf-btn primary icon-ticket" href="'.$event['ticket_link'].'">'.__('Buy tickets', 'san-carlo-theme').'</a>';
                                        $event_code .= '<a class="bf-link primary" href="'.$event['permalink'].'">'.__('Discover more', 'san-carlo-theme').'</a>';
                                    $event_code .= '</div>';
                    
                                $event_code .= '</div>';
                            }
                        }
                    }
                    endif;
                endforeach;
            }
        }

        return $event_code;
    }

    private function _getWeekDayInRange($weekday, $dateFromString, $dateToString, $format = 'Y-m-d')
    {
        $dateFrom = new \DateTime();
        $dateTo = new \DateTime();
        $dateFrom->setTimestamp($dateFromString);
        $dateTo->setTimestamp($dateToString);
        $dates = [];

        if ($dateFrom > $dateTo) {
            return $dates;
        }

        if (date('N', strtotime($weekday)) != $dateFrom->format('N')) {
            $dateFrom->modify("next $weekday");
        }

        while ($dateFrom <= $dateTo) {
            $dates[] = $dateFrom->format($format);
            $dateFrom->modify('+1 week');
        }

        return $dates;
    }


    private function _showDay($day, $month, $year) {

        if ($day == null && $month != $this->active_month && $year != $this->active_year)
            $day = 1;
        else
            $day = $this->active_day;

        $newYear = strtotime($year . '-' . $month . '-' . $day);
        $newWeek = strtotime($year . '-' . $month . '-1');

        $numDays = date('t', $newYear);
        $numDaysLastMonth = date('j', strtotime('last day of previous month', $newYear));
        $nDays = [0 => __('Mon', 'san-carlo-theme'), 1 => __('Tue', 'san-carlo-theme'), 2 => __('Wed', 'san-carlo-theme'), 3 => __('Thu', 'san-carlo-theme'), 4 => __('Fri', 'san-carlo-theme'), 5 => __('Sat', 'san-carlo-theme'), 6 => __('Sun', 'san-carlo-theme')];
        $firstDayOfWeek = array_search(wp_date('D', $newWeek), $nDays);
        $maxNum = $this->_weeksInMonth($month, $year) >= 5 ? 42 : 35;
        $lastMonthDay = date('j', strtotime('last day of this month', $newYear));

        $html = '';

        foreach ($nDays as $nDay) {
            $html .= '<div class="day_name">'. substr($nDay, 0, 1) .'</div>';
        }
        for ($i = $firstDayOfWeek; $i > 0; $i--) {
            $html .= '<div class="day_num ignore">'. ($numDaysLastMonth-$i+1) .'</div>';
        }

        for ($i = 1; $i <= $numDays; $i++) {
            $selected = '';
            $controls = '';
            if ($i == $day && $month == $this->active_month && $year == $this->active_year) {
                $selected = ' selected';
            }
            $html .= '<div class="day_num' . $selected . '">
                    <span>'. $i .'</span>'.
                        $this->_addEventToCal($i, $month, $year, $month, $lastMonthDay, false).
                        $controls.
                    '</div>';
        }

        for ($i = 1; $i <= ($maxNum - $numDays - $firstDayOfWeek); $i++) {
            $html .= '<div class="day_num ignore">
                    <span>'. $i .'</span>'.
                        $this->_addEventToCal($i, (intval($month)+1), $year, $month, $lastMonthDay, false).
                    '</div>';
        }
        $html .= '</div>';
		return $html;
	}


    private function _createNavi($year = null, $month = null){

        if (is_null($year) && isset($_GET['y'])) {
			$year = $_GET['y'];
		} else if (is_null($year)) {
			$year = $this->active_year;
		}

		if (is_null($month) && isset($_GET['month'])) {
			$month = $_GET['month'];
		} else if (is_null($month)) {
			$month = $this->active_month;
		}

		$nextMonth = $month == 12 ? 1 : intval($month)+1;
		$nextYear = $month == 12 ? intval($year)+1 : $year;

		$preMonth = $month == 1 ? 12 : intval($month)-1;
		$preYear = $month == 1 ? intval($year)-1 : $year;
        $base = is_admin() ? 'edit.php?post_type=prenotazioni&page=prenotazioni-cal&' : '?';

		$html =
			'<div id="calendar" class="header-cal">'.
				'<div class="month-year">'.
                    '<select id="month">';
                        // '<option value="'.$month.'">'.wp_date('F', strtotime($year . '-' . $month . '-1')).'</option>';
                        for($i=1; $i <= 12; $i++){
                            $selected = $month == $i ? 'selected="selected"' : '';
                            $html .= '<option value="'.sprintf('%02d', $i).'" '.$selected.'>'.wp_date('F', strtotime($year .'-'. $i .'-1')).' '.$year.'</option>';
                        }
          $html .= '</select>'.
                    '<div class="cal-nav">'.
                        '<a class="choose-month hidden" href="#" data-year="'.$year.'" data-admin="'.$base.'"></a>'.
                        '<a class="prev"> <i class="bf-icon icon-chevron left"></i></a>'.
                        '<a class="next"> <i class="bf-icon icon-chevron"></i></a>'.
                    '</div>'.
                '</div>'.
			'</div>';

        return $html;
	}

    public function add_event($id, $title, $venue = '', $type = '', $id_prod) {
        
        $this->events[] = [
            'ID' => $id,
            'title' => $title,
            'venue' => $venue,
            // 'data' => $date,
            // 'days' => $days,
            'type' => $type,
            'prod' => $id_prod
        ];
    }


    public function show( $month = null, $year = null, $atts = false, $date = null ) {

        $day = null;

        if (isset($_GET['month']) && isset($_GET['y'])) {
			$month = $_GET['month'];
            $year = $_GET['y']; //(string) get_query_var('y');
		} else if ((is_null($month) || (string) get_query_var('month') == '0') && (is_null($year) || (string) get_query_var('y') == '0') ) {
			$month = $this->active_month;
            $year = $this->active_year;
            $day = $this->active_day;
		}

        $html = '<div class="calendar"><div id="close-cal"><i class="bf-icon icon-remove" onclick="closeCal()"></i></div>';
            $html .= $this->_createNavi($year, $month);
            $html .= '<div class="days">';
                $html .= $this->_showDay($day, $month, $year);
            $html .= '</div>';
            $html .= '<div id="controls">
                        <a class="button prev"><i class="bf-icon icon-chevron left"></i></a>
                        <a class="button next"><i class="bf-icon icon-chevron"></i></a>
                        <input id="active-slide" type="hidden" value="1" max-value="">
                    </div>';
        $html .= '</div>';

        $html .= '<script>
        function closeCal() {
            let cal = jQuery(".bf-calendar-choice");
            cal.removeClass("view");
    
            jQuery("#filtri li.calendar").removeClass("open");
        }
        </script>';

        return $html;
    }
}