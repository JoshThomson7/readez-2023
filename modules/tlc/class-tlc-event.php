<?php
/**
 * TLC Event
 *
 * @author FL1 Digital
 * @version 1.0
 */

 // Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class TLC_Event {

    protected $id;
    protected $EM_Event;
    
    /**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0
	 * @access public
	 * @param int $id
	 */
    public function __construct($id = null) {

        $this->id = $id ?? 0;
		$this->EM_Event = em_get_event($this->id, 'post_id');

    }

    /**
     * Gets the tier ID.
     * @return int
     */
    public function id() {

        return $this->id;

    }

    /**
     * Gets the merchant ID.
     * @return int
     */
    public function title() {

        return $this->EM_Event->output("#_EVENTNAME");

    }

    /**
     * Returns permalink
     */
    public function url() {

        return $this->EM_Event->output("#_EVENTURL");

    }

	/**
	 * Returns event dates
	 * 
	 * @param string $type
	 * @param string $format
	 */
    public function date($type = 'start', $format = '') {

		$dates = $this->EM_Event->output("#_EVENTDATES");
		$dates_array = explode(' - ', $dates);
		$start_date = $dates_array[0];
		$end_date = $dates_array[1];

		switch ($type) {
			case 'start':
				$date = $start_date;
				break;
			case 'end':
				$date = $end_date;
				break;
			default:
				$date = $dates;
				break;
		}

		if($format) {
			$date = new DateTime($date, wp_timezone());

			if($format !== 'oject') {
				$date = $date->format($format);
			}
		}

		return $date;

	}

	/**
	 * Returns start time
	 * @return string
	 */
    public function start_time() {

		return $this->EM_Event->output("#_24HSTARTTIME");

	}

	/**
	 * Returns end time
	 * @return string
	 */
    public function end_time() {

		return $this->EM_Event->output("#_24HENDTIME");

	}
	
	/**
	 * Returns location ID
	 * @return int
	 */
    public function location_id() {

		return $this->EM_Event->output("#_LOCATIONPOSTID");

	}

	/**
	 * Returns location name
	 * @return string
	 */
    public function location_name() {

		return $this->EM_Event->output("#_LOCATIONNAME");

	}

	/**
	 * Returns delivery
	 * @return string
	 */
    public function audience() {

		return get_field('event_audience', $this->id);

	}

	/**
	 * Returns delivery
	 * @return string
	 */
    public function delivery() {

		return get_field('event_delivery', $this->id);

	}

	/**
	 * Returns price
	 * @return string
	 */
    public function price($free = false) {

		$price = $this->EM_Event->output("#_EVENTPRICERANGE");
		return $free && $price === '£0.00' ? 'Free' : $price;

	}

    /**
     * Returns content.
     * 
     * @return string
     */
    public function content($trunc = 0) {

		$content = $this->EM_Event->output("#_EVENTNOTES");
        return $trunc ? FL1_Helpers::trunc($content, $trunc) : $content;

    }

    /**
     * Returns the event booking form
     * 
     * @return string
     */
    public function booking_form() {

        return $this->EM_Event->output("#_BOOKINGFORM");

    }

}

