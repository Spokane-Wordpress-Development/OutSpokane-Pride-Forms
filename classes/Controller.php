<?php

namespace OutSpokane;

use \Stripe\Stripe;
use \Stripe\Charge;
use \Stripe\Error\Card;

class Controller {

	const VERSION = '1.7.0';
	const VERSION_JS = '1.7.5';
	const VERSION_CSS = '1.5.0';

	public $action = '';
	public $data = '';
	public $return = '';
	public $attributes = array();
	public $base_page = '';
	public $error = '';
	public $successes = '';

	/**
	 *
	 */
	public function activate()
	{
		add_option( 'pride_forms_version', self::VERSION );

		require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
		global $wpdb;

		/* create tables */
		$charset_collate = '';
		if ( ! empty( $wpdb->charset ) )
		{
			$charset_collate .= "DEFAULT CHARACTER SET " . $wpdb->charset;
		}
		if ( ! empty( $wpdb->collate ) )
		{
			$charset_collate .= " COLLATE " . $wpdb->collate;
		}

		/* cruise_entries table */
		$table = $wpdb->prefix . CruiseEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`tickets_sent` TINYINT(4) DEFAULT NULL,
					`is_will_call` TINYINT(4) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* festival_entries table */
		$table = $wpdb->prefix . FestivalEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_type_id` INT(11) DEFAULT NULL,
					`is_corner_booth` TINYINT(4) DEFAULT NULL,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`price_for_corner_booth` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`description` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* parade_entries table */
		$table = $wpdb->prefix . ParadeEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`entry_types` TEXT DEFAULT NULL,
					`float_parking_spaces` INT(11) DEFAULT NULL,
					`donation_amount` DECIMAL(11,2) DEFAULT NULL,
					`description` TEXT DEFAULT NULL,
					`needs_amped_sound` TINYINT(4) DEFAULT NULL,
					`group_size` INT(11) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* murder_mystery_entries table */
		$table = $wpdb->prefix . MurderMysteryEntry::TABLE_NAME;
        $sql = "CREATE TABLE " . $table . " (
                id INT(11) NOT NULL AUTO_INCREMENT,
                entry_year INT(11) DEFAULT NULL,
                email VARCHAR(50) DEFAULT NULL,
                phone VARCHAR(50) DEFAULT NULL,
                organization VARCHAR(150) DEFAULT NULL,
                first_name VARCHAR(50) DEFAULT NULL,
                last_name VARCHAR(50) DEFAULT NULL,
                address VARCHAR(50) DEFAULT NULL,
                city VARCHAR(50) DEFAULT NULL,
                state VARCHAR(2) DEFAULT NULL,
                zip VARCHAR(10) DEFAULT NULL,
                qty INT(11) DEFAULT NULL,
                vegetarian_qty INT(11) DEFAULT NULL,
                is_sponsor TINYINT(4) DEFAULT NULL,
                is_vip TINYINT(4) DEFAULT NULL,
                meals TEXT DEFAULT NULL,
                is_upgraded TINYINT(4) DEFAULT NULL,
                price_per_qty DECIMAL(11,2) DEFAULT NULL,
                payment_method_id INT(11) DEFAULT NULL,
                paid_at DATETIME DEFAULT NULL,
                payment_amount DECIMAL(11,2) DEFAULT NULL,
                payment_confirmation_number VARCHAR(50) DEFAULT NULL,
                notes TEXT DEFAULT NULL,
                tickets_sent TINYINT(4) DEFAULT NULL,
                created_at DATETIME DEFAULT NULL,
                updated_at DATETIME DEFAULT NULL,
                PRIMARY KEY  (id)
                )";
        $sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
        dbDelta( $sql );

		/* donations table */
		$table = $wpdb->prefix . Donation::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`donation_amount` DECIMAL(11,2) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* flag_handles table */
		$table = $wpdb->prefix . FlagHandle::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`message` TEXT DEFAULT NULL,
					`color` VARCHAR(25) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* sponsorships table */
		$table = $wpdb->prefix . Sponsorship::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`position` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`local_first_name` VARCHAR(50) DEFAULT NULL,
					`local_last_name` VARCHAR(50) DEFAULT NULL,
					`local_position` VARCHAR(50) DEFAULT NULL,
					`local_address` VARCHAR(50) DEFAULT NULL,
					`local_city` VARCHAR(50) DEFAULT NULL,
					`local_state` VARCHAR(2) DEFAULT NULL,
					`local_zip` VARCHAR(10) DEFAULT NULL,
					`local_email` VARCHAR(50) DEFAULT NULL,
					`local_phone` VARCHAR(50) DEFAULT NULL,
					`url` VARCHAR(150) DEFAULT NULL,
					`level` VARCHAR(50) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`amount` DECIMAL(11,2) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

		/* bowling table */
		$table = $wpdb->prefix . BowlingEntry::TABLE_NAME;
		if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
			$sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
			$sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
			dbDelta( $sql );
		}

        /* food_truck table */
        $table = $wpdb->prefix . FoodTruck::TABLE_NAME;
        if( $wpdb->get_var( "SHOW TABLES LIKE '" . $table . "'" ) != $table ) {
            $sql = "
				CREATE TABLE `" . $table . "`
				(
					`id` INT(11) NOT NULL AUTO_INCREMENT,
					`entry_year` INT(11) DEFAULT NULL,
					`email` VARCHAR(50) DEFAULT NULL,
					`phone` VARCHAR(50) DEFAULT NULL,
					`organization` VARCHAR(150) DEFAULT NULL,
					`first_name` VARCHAR(50) DEFAULT NULL,
					`last_name` VARCHAR(50) DEFAULT NULL,
					`address` VARCHAR(50) DEFAULT NULL,
					`city` VARCHAR(50) DEFAULT NULL,
					`state` VARCHAR(2) DEFAULT NULL,
					`zip` VARCHAR(10) DEFAULT NULL,
					`qty` INT(11) DEFAULT NULL,
					`price_per_qty` DECIMAL(11,2) DEFAULT NULL,
					`payment_method_id` INT(11) DEFAULT NULL,
					`paid_at` DATETIME DEFAULT NULL,
					`payment_amount` DECIMAL(11,2) DEFAULT NULL,
					`payment_confirmation_number` VARCHAR(50) DEFAULT NULL,
					`notes` TEXT DEFAULT NULL,
					`description` TEXT DEFAULT NULL,
					`created_at` DATETIME DEFAULT NULL,
					`updated_at` DATETIME DEFAULT NULL,
					PRIMARY KEY (`id`)
				)";
            $sql .= $charset_collate . ";"; // new line to avoid PHP Storm syntax error
            dbDelta( $sql );
        }
	}

	/**
	 *
	 */
	public static function uninstall()
	{
		/* Let's not delete tables */
	}

	/**
	 *
	 */
	public function init()
	{
		$parts = explode('?', $_SERVER['REQUEST_URI']);
		$this->base_page = $parts[0];

		wp_enqueue_script( 'out-spokane-pride-forms-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/pride-forms.js', array( 'jquery' ), (WP_DEBUG) ? time() : self::VERSION_JS, TRUE );
		wp_localize_script( 'out-spokane-pride-forms-js', 'prideforms', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'entry_nonce' => wp_create_nonce( 'entry-nonce' )
		) );
		wp_enqueue_script( 'out-spokane-stripe', 'https://js.stripe.com/v2/', array( 'jquery' ), time(), TRUE);

		wp_enqueue_style( 'out-spokane-pride-forms-bootstrap-grid', plugin_dir_url( dirname( __FILE__ ) ) . 'css/grid12.css', array(), (WP_DEBUG) ? time() : self::VERSION_CSS );
		wp_enqueue_style( 'out-spokane-pride-forms-bootstrap-tables', plugin_dir_url( dirname( __FILE__ ) ) . 'css/bootstrap-tables.css', array(), (WP_DEBUG) ? time() : self::VERSION_CSS );
		wp_enqueue_style( 'out-spokane-pride-forms-css', plugin_dir_url( dirname( __FILE__ ) ) . 'css/pride-forms.css', array(), (WP_DEBUG) ? time() : self::VERSION_CSS );
	}

	/**
	 *
	 */
	public function enqueueAdminScripts()
	{
		wp_enqueue_script( 'out-spokane-admin-js', plugin_dir_url( dirname( __FILE__ ) ) . 'js/admin.js', array( 'jquery' ), (WP_DEBUG) ? time() : self::VERSION_JS, TRUE );
	}

	public function param( $param, $default='' )
	{
		return (isset($_REQUEST[$param])) ? htmlspecialchars($_REQUEST[$param]) : $default;
	}

	public function queryVars( $vars )
	{
		$vars[] = 'sq_action';
		$vars[] = 'sq_data';
		return $vars;
	}

	/**
	 * @param $attributes
	 *
	 * @return string
	 */
	public function shortCode( $attributes )
	{
		$this->attributes = shortcode_atts( array(
			'form' => '',
			'year' => date( 'Y' ),
			'corner_booth' => 'yes'
		), $attributes );

		switch ( $this->getAttribute('form') )
		{
			case 'cruise':
			case 'festival':
			case 'murder_mystery':
			case 'parade':
			case 'donation':
			case 'flag':
			case 'sponsorship':
			case 'bowling':
            case 'food_truck':
				return $this->return . $this->returnOutputFromPage( $this->getAttribute('form') );
		}

		return $this->return;
	}

	/**
	 * @param $page
	 *
	 * @return string
	 */
	private function returnOutputFromPage( $page )
	{
		ob_start();
		include( dirname( __DIR__ ) . '/includes/' . $page . '.php' );
		return ob_get_clean();
	}

	/**
	 * @param $attribute
	 *
	 * @return string
	 */
	public function getAttribute( $attribute )
	{
		if (array_key_exists($attribute, $this->attributes))
		{
			return strtolower($this->attributes[$attribute]);
		}

		return '';
	}

	/**
	 *
	 */
	public function formCapture()
	{
		if ( isset( $_POST['pride_export'] ) )
		{
			Entry::exportToCsv();
			exit;
		}

		if ( isset( $_POST['edit_outspokane_entry'] ) )
		{
			switch ( $_POST['form'] )
			{
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'parade':
					$entry = new ParadeEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				case 'flag':
					$entry = new FlagHandle( $_POST['id'] );
					break;
				case 'sponsorship':
					$entry = new Sponsorship( $_POST['id'] );
					break;
				case 'bowling':
					$entry = new BowlingEntry( $_POST['id'] );
					break;
                case 'food_truck':
                    $entry = new FoodTruck( $_POST['id'] );
                    break;
				default:
					$entry = new MurderMysteryEntry( $_POST['id'] );
			}

			$entry
				->setEntryYear( $_POST['entry_year'] )
				->setOrganization( $_POST['organization'] )
				->setFirstName( $_POST['first_name'] )
				->setLastName( $_POST['last_name'] )
				->setEmail( $_POST['email'] )
				->setPhone( $_POST['phone'] )
				->setAddress( $_POST['address'] )
				->setCity( $_POST['city'] )
				->setState( $_POST['state'] )
				->setZip( $_POST['zip'] )
				->setQty( $_POST['qty'] );

			if ( $_POST['form'] == 'festival' )
			{
				$entry
					->setEntryTypeId( $_POST['entry_type_id'] )
					->setIsCornerBooth( $_POST['is_corner_booth'] )
					->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) )
					->setPriceForCornerBooth( preg_replace( '/[^0-9\.]/', '', $_POST['price_for_corner_booth'] ) )
					->setDescription( $_POST['description'] );
			}
			elseif ( $_POST['form'] == 'food_truck' )
            {
                $entry
                    ->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) )
                    ->setDescription( $_POST['description'] );
            }
			elseif ( $_POST['form'] == 'cruise' )
			{
				$entry
                    ->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) )
                    ->setIsWillCall( $_POST['is_will_call'] );
			}
			elseif ( $_POST['form'] == 'parade' )
			{
				$entry
					->setEntryTypes( $_POST['parade_entry_type'] )
					->setDescription( $_POST['description'] )
					->setFloatParkingSpaces( $_POST['float_parking_spaces'] )
					->setFloatParkingSpaceCost( preg_replace( '/[^0-9\.]/', '', $_POST['float_parking_space_cost'] ) )
					->setNeedsAmpedSound( $_POST['needs_amped_sound'] )
					->setGroupSize( $_POST['group_size'] );
			}
			elseif ( $_POST['form'] == 'murder_mystery' )
			{
				$entry
					->setIsSponsor( $_POST['is_sponsor'] )
					->setPricePerQty( preg_replace( '/[^0-9\.]/', '', $_POST['price_per_qty'] ) )
					->setIsUpgraded( $_POST['is_upgraded'] )
					->setVegetarianQty( $_POST['vegetarian_qty'] );
			}
			elseif ( $_POST['form'] == 'donation' )
			{
				$entry
					->setDonationAmount( $_POST['donation_amount'] );
			}
			elseif ( $_POST['form'] == 'flag' )
			{
				$entry
					->setMessage( $_POST['message'] )
					->setColor( $_POST['color'] );
			}
			elseif ( $_POST['form'] == 'sponsorship' )
			{
				$entry
					->setPosition( $_POST['position'] )
					->setLocalPosition( $_POST['local_position'] )
					->setAmount( $_POST['amount'] )
					->setUrl( $_POST['url'] )
					->setLevel( $_POST['level'] )
					->setLocalFirstName( $_POST['local_first_name'] )
					->setLocalLastName( $_POST['local_last_name'] )
					->setLocalAddress( $_POST['local_address'] )
					->setLocalCity( $_POST['local_city'] )
					->setLocalState( $_POST['local_state'] )
					->setLocalZip( $_POST['local_zip'] )
					->setLocalEmail( $_POST['local_email'] )
					->setLocalPhone( $_POST['local_phone'] );
			}

			$entry->update();
			header( 'Location:admin.php?page=' . $_POST['return'] . '&action=view&id=' . $entry->getId() );
			exit;
		}

		if ( isset( $_POST['pride_action'] ) )
		{
			if ( wp_verify_nonce( $_POST['_wpnonce'], 'pride-nonce' ) )
			{
				if ( $_POST['pride_action'] == 'cc' )
				{
					$parts = explode( '-', $_POST['txid'] );
					if ( count( $parts ) == 2 )
					{
						if ( is_numeric( $parts[1] ) )
						{
							switch ( $_POST['form'] )
							{
								case 'cruise':
									$entry = new CruiseEntry( $parts[1] );
									$title = 'Pride Cruise';
									break;
								case 'festival':
									$entry = new FestivalEntry( $parts[1] );
									$title = 'Pride Festival Entry';
									break;
                                case 'food_truck':
                                    $entry = new FoodTruck( $parts[1] );
                                    $title = 'Food Truck Entry';
                                    break;
								case 'murder_mystery':
									$entry = new MurderMysteryEntry( $parts[1] );
									$title = 'Murder Mystery Ticket';
									break;
								case 'donation':
									$entry = new Donation( $parts[1] );
									$title = 'Donation';
									break;
								case 'flag':
									$entry = new FlagHandle( $parts[1] );
									$title = 'Flag Handle';
									break;
								case 'sponsorship':
									$entry = new Sponsorship( $parts[1] );
									$title = 'Sponsorship';
									break;
								case 'bowling':
									$entry = new BowlingEntry( $parts[1] );
									$title = 'Bowling Ticket';
									break;
								default: /* 'parade' */
									$entry = new ParadeEntry( $parts[1] );
									$title = 'Pride Parade Entry';
							}

							if ( $entry->getCreatedAt() !== NULL && isset( $_POST['stripeToken'] ) && strlen( $_POST['stripeToken'] ) > 0 )
							{
								$stripe_keys = Entry::getStripeKeys();
								Stripe::setApiKey( $stripe_keys['secret'] );
								Stripe::setApiVersion( '2016-03-07' );

								try
								{
									/** @var \Stripe\Charge $charge */
									$charge = Charge::create( array(
										'amount' => round( $entry->getAmountDue() * 100 ),
										'currency' => 'usd',
										'source' => $_POST['stripeToken'],
										'description' => $entry->getEntryYear() . ' ' . $title
									) );

									$entry
										->setPaidAt( time() )
										->setPaymentMethodId( Entry::PAYMENT_METHOD_CARD )
										->setPaymentAmount( $entry->getAmountDue() )
										->setPaymentConfirmationNumber( $charge->id )
										->update();

									header( 'Location:' . $_POST['_wp_http_referer'] );
									exit;
								}
								catch ( Card $e )
								{
									/* card was declined */
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 *
	 */
	public function addMenus()
	{
		add_menu_page('OutSpokane', 'OutSpokane', 'manage_options', 'outspokane', array( $this, 'showSettingsPage' ), 'dashicons-flag');
		add_submenu_page('outspokane', 'Settings', 'Settings', 'manage_options', 'outspokane');
		add_submenu_page('outspokane', 'Short Code', 'Short Code', 'manage_options', 'outspokane_shortcode', array($this, 'showShortCode'));
		add_submenu_page('outspokane', 'Cruise Entries', 'Cruise Entries', 'manage_options', 'outspokane_cruise', array($this, 'showCruiseEntries'));
		add_submenu_page('outspokane', 'Parade Entries', 'Parade Entries', 'manage_options', 'outspokane_parade', array($this, 'showParadeEntries'));
		add_submenu_page('outspokane', 'Festival Entries', 'Festival Entries', 'manage_options', 'outspokane_festival', array($this, 'showFestivalEntries'));
		add_submenu_page('outspokane', 'Murder Mystery Entries', 'Murder Mystery Entries', 'manage_options', 'outspokane_murder_mystery', array($this, 'showMurderMysteryEntries'));
		add_submenu_page('outspokane', 'Donations', 'Donations', 'manage_options', 'outspokane_donation', array($this, 'showDonations'));
		add_submenu_page('outspokane', 'Flag Handles', 'Flag Handles', 'manage_options', 'outspokane_flag', array($this, 'showFlagHandles'));
		add_submenu_page('outspokane', 'Sponsorships', 'Sponsorships', 'manage_options', 'outspokane_sponsorship', array($this, 'showSponsorships'));
		add_submenu_page('outspokane', 'Bowling Tickets', 'Bowling Tickets', 'manage_options', 'outspokane_bowling', array($this, 'showBowlingEntries'));
        add_submenu_page('outspokane', 'Food Trucks', 'Food Trucks', 'manage_options', 'outspokane_food_truck', array($this, 'showFoodTruckEntries'));
		
		/* I guess this is how to add a page without adding a menu */
		add_submenu_page(NULL, 'Edit Entry', 'Edit Entry', 'manage_options', 'outspokane_edit_entry', array($this, 'editEntry'));
	}

	public function registerSettings()
	{
		register_setting( 'outspokane_settings', 'pride_forms_stripe_test_secret_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_test_pub_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_live_secret_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_live_pub_key' );
		register_setting( 'outspokane_settings', 'pride_forms_stripe_mode' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_cruise_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_donation_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_festival_form' );
        register_setting( 'outspokane_settings', 'pride_forms_disable_food_truck_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_murder_mystery_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_parade_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_flag_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_bowling_form' );
		register_setting( 'outspokane_settings', 'pride_forms_disable_sponsorship_form' );
	}

	/**
	 *
	 */
	public function showSettingsPage()
	{
		include( dirname( __DIR__ ) . '/includes/settings.php');
	}

	/**
	 *
	 */
	public function showShortCode()
	{
		include( dirname( __DIR__ ) . '/includes/shortcode.php');
	}

	/**
	 *
	 */
	public function showCruiseEntries()
	{
		include( dirname( __DIR__ ) . '/includes/cruise_entries.php');
	}

	/**
	 *
	 */
	public function showParadeEntries()
	{
		include( dirname( __DIR__ ) . '/includes/parade_entries.php');
	}

	/**
	 *
	 */
	public function showFestivalEntries()
	{
		include( dirname( __DIR__ ) . '/includes/festival_entries.php');
	}

    /**
     *
     */
    public function showFoodTruckEntries()
    {
        include( dirname( __DIR__ ) . '/includes/food_truck_entries.php');
    }

	/**
	 *
	 */
	public function showMurderMysteryEntries()
	{
		include( dirname( __DIR__ ) . '/includes/murder_mystery_entries.php');
	}

	/**
	 *
	 */
	public function showDonations()
	{
		include( dirname( __DIR__ ) . '/includes/donations.php');
	}

	/**
	 *
	 */
	public function showFlagHandles()
	{
		include( dirname( __DIR__ ) . '/includes/flag_handles.php');
	}

	/**
	 *
	 */
	public function showSponsorships()
	{
		include( dirname( __DIR__ ) . '/includes/sponsorships.php');
	}

	/**
	 *
	 */
	public function showBowlingEntries()
	{
		include( dirname( __DIR__ ) . '/includes/bowling_entries.php');
	}

	/**
	 * 
	 */
	public function editEntry()
	{
		include( dirname( __DIR__ ) . '/includes/edit_entry.php');
	}

	/**
	 * 
	 */
	public function handleNewAjaxEntry()
	{
		$response = array(
			'success' => 1,
			'error' => ''
		);

		if ( isset( $_POST['action'] ) && $_POST['action'] == 'error-report' )
		{
			$headers = array(
				'Content-Type: text/html; charset=UTF-8',
				'From info@outspokane.org'
			);
			wp_mail( 'spokanetony@gmail.com', 'OutSpokane Server Error', 'The following error just occurred: ' . $_POST['error'], $headers );
			exit;
		}

		if ( wp_verify_nonce($_POST['entry_nonce'], 'entry-nonce'))
		{
			if ( ! filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL ) )
			{
				$response['success'] = 0;
				$response['error'] = 'The email address you entered is not valid';
			}
			elseif ( $_POST['form'] == 'donation' && ( preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) == '' || preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) == 0 ) )
			{
				$response['success'] = 0;
				$response['error'] = 'Please enter a valid donation amount';
			}
			elseif ( $_POST['form'] == 'sponsorship' && ( preg_replace( '/[^0-9\.]/', '', $_POST['amount'] ) == '' || preg_replace( '/[^0-9\.]/', '', $_POST['amount'] ) == 0 ) )
			{
				$response['success'] = 0;
				$response['error'] = 'Please enter a valid sponsorship amount';
			}
			else
			{
				switch ( $_POST['form'] )
				{
					case 'cruise':

						$subject = 'Cruise';
						$entry = new CruiseEntry;
						$entry
							->setQty( $_POST['qty'] )
							->setPricePerQty( CruiseEntry::PRICE_PER_TICKET )
							->setPaymentMethodId( Entry::PAYMENT_METHOD_CARD )
                            ->setIsWillCall( $_POST['is_will_call'] );

						break;

					case 'festival':

						$subject = 'Pride Festival';
						$entry = new FestivalEntry;
						$entry
							->setQty( 1 )
							->setDescription( $_POST['description'] )
							->setEntryTypeId( $_POST['entry_type_id'] )
							->setPriceForCornerBooth( FestivalEntry::CORNER_BOOTH_FEE )
							->setPricePerQty( $entry->getEntryTypePrice( $_POST['entry_type_id'] ) )
							->setIsCornerBooth( ( $_POST['entry_type_id'] == FestivalEntry::ENTRY_TYPE_SPONSOR ) ? FALSE : $_POST['corner_booth'] );

						break;

                    case 'food_truck':

                        $subject = 'Food Truck';
                        $entry = new FoodTruck;
                        $entry
                            ->setQty( 1 )
                            ->setDescription( $_POST['description'] )
                            ->setPricePerQty( FoodTruck::ENTRY_FEE );

                        break;

					case 'murder_mystery':

						$subject = 'Murder Mystery';
						/** @var MurderMysteryEntry $entry */
						$entry = new MurderMysteryEntry;
						$entry->setMeals( $_POST['meals'] );

						if ( $_POST['is_sponsor'] == 1 )
						{
							$entry
								->setQty( 10 )
								->setIsSponsor( TRUE )
								->setPricePerQty( 0 );
						}
                        elseif ( $_POST['is_vip'] == 1 )
                        {
                            $entry
                                ->setQty( 1 )
                                ->setIsVip( TRUE )
                                ->setPricePerQty( MurderMysteryEntry::VIP_TABLE_PRICE );
                        }
						else
						{
							$entry
								->setQty( $_POST['qty'] )
								->setPricePerQty( MurderMysteryEntry::TICKET_PRICE );
						}

						break;

					case 'donation':

						$subject = 'Donation';
						$entry = new Donation;
						$entry->setDonationAmount( preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) );
						break;

					case 'flag':

						$subject = 'Flag Handle';
						$entry = new FlagHandle;
						$entry
							->setColor( $_POST['color'] )
							->setMessage( $_POST['message'] )
							->setPricePerQty( ( $_POST['color'] == 'Black' ) ? FlagHandle::PRICE_PER_HANDLE_BLACK : FlagHandle::PRICE_PER_HANDLE_OTHER )
							->setQty( 1 );
						break;

					case 'sponsorship':

						$subject = 'Sponsorship';
						$entry = new Sponsorship;
						$entry
							->setAmount( preg_replace( '/[^0-9\.]/', '', $_POST['amount'] ) )
							->setPosition( $_POST['position'] )
							->setLocalPosition( $_POST['local_position'] )
							->setUrl( $_POST['url'] )
							->setLevelFromAmount()
							->setLocalFirstName( $_POST['local_first_name'] )
							->setLocalLastName( $_POST['local_last_name'] )
							->setLocalAddress( $_POST['local_address'] )
							->setLocalCity( $_POST['local_city'] )
							->setLocalState( $_POST['local_state'] )
							->setLocalZip( $_POST['local_zip'] )
							->setLocalEmail( $_POST['local_email'] )
							->setLocalPhone( $_POST['local_phone'] )
							->setQty( 1 );
						break;

					case 'bowling':

						$subject = 'Bowling Tickets';
						$entry = new BowlingEntry;
						$entry
							->setQty( $_POST['qty'] )
							->setPricePerQty( BowlingEntry::PRICE_PER_TICKET )
							->setPaymentMethodId( Entry::PAYMENT_METHOD_CARD );

						break;

					default: /* 'parade' */

						$subject = 'Pride Parade';
						$entry = new ParadeEntry;
						$entry
							->setEntryTypes( stripslashes( $_POST['entry_types'] ) )
							->setFloatParkingSpaces( $_POST['float_parking_spaces'] )
							->setFloatParkingSpaceCost( ParadeEntry::FLOAT_PARKING_SPACE_COST )
							->setDonationAmount( preg_replace( '/[^0-9\.]/', '', $_POST['donation_amount'] ) )
							->setDescription( $_POST['description'] )
							->setNeedsAmpedSound( $_POST['needs_amped_sound'] )
							->setGroupSize( preg_replace( '/\D/', '', $_POST['group_size'] ) )
							->setQty( 1 );
				}

				$entry
					->setEntryYear( $_POST['entry_year'] )
					->setOrganization( $_POST['organization'] )
					->setFirstName( $_POST['first_name'] )
					->setLastName( $_POST['last_name'] )
					->setEmail( $_POST['email'] )
					->setPhone( $_POST['phone'] )
					->setAddress( $_POST['address'] )
					->setCity( $_POST['city'] )
					->setState( $_POST['state'] )
					->setZip( $_POST['zip'] )
					->setCreatedAt( time() )
					->setUpdatedAt( time() )
					->create();

				$fields = array(
					'Entry Year',
					'Organization',
					'First Name',
					'Last Name',
					'Email',
					'Phone',
					'Address',
					'City',
					'State',
					'Zip',
					'Qty'
				);

				$subject = 'OutSpokane Receipt - ' . $entry->getEntryYear() . ' ' . $subject ;
				$body = '
					<p>Thank you! Below are the details of your transaction:</p>
					<table>
						<tr>
							<td><strong>Title:</strong></td>
							<td>' . $entry->getEntryYear() . ' ' . $subject . '</td>
						</tr>';

				if ( $_POST['form'] == 'flag' )
				{
					$body .= '
						<tr>
							<td><strong>Embroidered Name:</strong></td>
							<td>' . $entry->getMessage() . '</td>
						</tr>
						<tr>
							<td><strong>Color:</strong></td>
							<td>' . $entry->getColor() . '</td>
						</tr>';
				}

				foreach ( $fields as $field )
				{
					$body .= '
						<tr>
							<td><strong>' . $field . ':</strong></td>
							<td>' . $entry->getRaw( strtolower( str_replace( ' ', '_', $field ) ) ) . '</td>
						</tr>';
				}

                if ( $_POST['form'] == 'cruise' )
                {
                    $body .= '
						<tr>
							<td><strong>Ticket Delivery:</strong></td>
							<td>' . ( ( $entry->isWillCall() ) ? 'Will Call' : 'Mail' ) . '</td>
						</tr>';
                }


				$body .= '
						<tr>
							<td><strong>Total:</strong></td>
							<td>$' . number_format( $entry->getTotal(), 2 ) . '</td>
						</tr>
					</table>
					<p>View the complete details of your transaction here:</p>
					<p><a href="https://outspokane.org' . $_POST['path'] . '?txid=' . $entry->getCreatedAt() . '-' . $entry->getId() . '">https://outspokane.org' . $_POST['path'] . '?txid=' . $entry->getCreatedAt() . '-' . $entry->getId() . '</a></p>';

				$headers = array(
					'Content-Type: text/html; charset=UTF-8',
					'From info@outspokane.org'
				);

                if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                    wp_mail($_POST['email'], $subject, $body, $headers);
                }

				wp_mail( 'info@outspokane.org', 'BCC: ' . $subject, $body, $headers );

				$response['txid'] = $entry->getCreatedAt() . '-' . $entry->getId();
			}
		}
		else
		{
			$response['success'] = 0;
			$response['error'] = 'There was a problem. Please try again.';
		}

		header( 'Content-Type: application/json' );
		echo json_encode( $response );
		exit;
	}

	/**
	 *
	 */
	public function updateEntryNotes()
	{
		if ( isset($_POST['form']) && isset($_POST['id']) && isset($_POST['notes']) )
		{
			switch ($_POST['form'])
			{
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
                case 'food_truck':
                    $entry = new FoodTruck( $_POST['id'] );
                    break;
				case 'murder_mystery':
					$entry = new MurderMysteryEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				case 'flag':
					$entry = new FlagHandle( $_POST['id'] );
					break;
				case 'sponsorship':
					$entry = new Sponsorship( $_POST['id'] );
					break;
				case 'bowling':
					$entry = new BowlingEntry( $_POST['id'] );
					break;
				default: /* parade */
					$entry = new ParadeEntry( $_POST['id'] );
			}

			if ( $entry->getCreatedAt() !== NULL )
			{

				$entry
					->setNotes( $_POST['notes'] )
					->update();

				echo 1;
				exit;
			}
		}

		echo 0;
	}

	/**
	 *
	 */
	public function updateEntryDetails()
	{
		switch ( $_POST['form'] )
		{
			case 'cruise':
				$entry = new CruiseEntry( $_POST['id'] );
				break;
			case 'festival':
				$entry = new FestivalEntry( $_POST['id'] );
				break;
            case 'food_truck':
                $entry = new FoodTruck( $_POST['id'] );
                break;
			case 'murder_mystery':
				$entry = new MurderMysteryEntry( $_POST['id'] );
				break;
			case 'donation':
				$entry = new Donation( $_POST['id'] );
				break;
			case 'flag':
				$entry = new FlagHandle( $_POST['id'] );
				break;
			case 'sponsorship':
				$entry = new Sponsorship( $_POST['id'] );
				break;
			case 'bowling':
				$entry = new BowlingEntry( $_POST['id'] );
				break;
			default: /* parade */
				$entry = new ParadeEntry( $_POST['id'] );
		}

		if ( $entry->getCreatedAt() !== NULL )
		{
			if ( $_POST['form'] == 'murder_mystery' )
			{
				$entry
					->setVegetarianQty( $_POST['vegetarian_qty'] )
					->setTicketsSent( $_POST['tickets_sent'] );
			}
			elseif ( $_POST['form'] == 'cruise' )
			{
				$entry->setTicketsSent( $_POST['tickets_sent'] );
			}
			elseif ( $_POST['form'] == 'donation' )
			{
				$entry->setDonationAmount( $_POST['donation_amount'] );
			}
			elseif ( $_POST['form'] == 'sponsorship' )
			{
				$entry
					->setAmount( preg_replace( '/[^0-9\.]/', '', $_POST['amount'] ) )
					->setPosition( $_POST['position'] )
					->setLocalPosition( $_POST['local_position'] )
					->setUrl( $_POST['url'] )
					->setLevel( $_POST['level'] )
					->setLocalFirstName( $_POST['local_first_name'] )
					->setLocalLastName( $_POST['local_last_name'] )
					->setLocalAddress( $_POST['local_address'] )
					->setLocalCity( $_POST['local_city'] )
					->setLocalState( $_POST['local_state'] )
					->setLocalZip( $_POST['local_zip'] )
					->setLocalEmail( $_POST['local_email'] )
					->setLocalPhone( $_POST['local_phone'] );
			}
			elseif ( $_POST['form'] == 'flag' )
			{
				$entry
					->setMessage( $_POST['message'] )
					->setColor( $_POST['color'] );
			}
		}

		$entry->update();

		echo 1;
		exit;
	}

	/**
	 *
	 */
	public function updateEntryPayment()
	{
		if ( isset($_POST['form']) && isset($_POST['id']) && isset($_POST['payment_method_id']) )
		{
			switch ($_POST['form'])
			{
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
                case 'food_truck':
                    $entry = new FoodTruck( $_POST['id'] );
                    break;
				case 'murder_mystery':
					$entry = new MurderMysteryEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				case 'flag':
					$entry = new FlagHandle( $_POST['id'] );
					break;
				case 'sponsorship':
					$entry = new Sponsorship( $_POST['id'] );
					break;
				case 'bowling':
					$entry = new BowlingEntry( $_POST['id'] );
					break;
				default: /* parade */
					$entry = new ParadeEntry( $_POST['id'] );
			}

			if ( $entry->getCreatedAt() !== NULL )
			{
				$entry->setPaymentMethodId( $_POST['payment_method_id'] );
				if ( $entry->getPaymentMethodId() === NULL )
				{
					$entry
						->setPaymentAmount( NULL )
						->setPaidAt( NULL );
				}
				else
				{
					$entry
						->setPaymentAmount( $entry->getTotal() )
						->setPaidAt( time() );
				}

				$entry->update();

				echo 1;
				exit;
			}
		}

		echo 0;
	}

	public function deleteEntry()
	{
		if ( isset($_POST['form']) && isset($_POST['id']) )
		{
			switch ($_POST['form'])
			{
				case 'cruise':
					$entry = new CruiseEntry( $_POST['id'] );
					break;
				case 'festival':
					$entry = new FestivalEntry( $_POST['id'] );
					break;
                case 'food_truck':
                    $entry = new FoodTruck( $_POST['id'] );
                    break;
				case 'murder_mystery':
					$entry = new MurderMysteryEntry( $_POST['id'] );
					break;
				case 'donation':
					$entry = new Donation( $_POST['id'] );
					break;
				case 'flag':
					$entry = new FlagHandle( $_POST['id'] );
					break;
				case 'sponsorship':
					$entry = new Sponsorship( $_POST['id'] );
					break;
				case 'bowling':
					$entry = new BowlingEntry( $_POST['id'] );
					break;
				default: /* parade */
					$entry = new ParadeEntry( $_POST['id'] );
			}

			$entry->delete();
			echo 'outspokane_' . $_POST['form'];
			exit;
		}

		echo 0;
	}
}