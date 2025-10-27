<?php

/**
 * Basic User data
 */
class User {

    /**
     * Summary of USER_ID
     * @var array
     */
    CONST USER_ID = [1, 2, 3];

    /**
     * Summary of __construct
     * @param int $id
     */
    public function __construct(public int $id) {
    }

    /**
     * Summary of get
     * @return User|null
     */
    public function get(): ?User {
        if (in_array($this->id, self::USER_ID)) {
            return new User($this->id);
        }
        return NULL;
    }

    /**
     * Summary of exchange
     * @return Exchange
     */
    public function exchange() {
        return new Exchange($this);
    }

    /**
     * Summary of display_user
     * @return void
     */
    public function display_user() {
        print_r("User Id is : " . $this->id . PHP_EOL);
    }

    /**
     * Summary of use_watt
     * @return void
     */
    public function use_watt() {
    }

    /**
     * Summary of produce_watt
     * @return void
     */
    public function produce_watt() {
    }
}

/**
 * Exchange system
 */
class Exchange {

    /**
     * Summary of user_watt_exchange
     * @var array
     */
    public $user_watt_exchange = [
        ["userId" => 1, "credits" => 0], 
        ["userId" => 2, "credits" => 2], 
        ["userId" => 3, "credits" => 3]
    ];

    /**
     * Summary of exchange_value
     * @var int
     */
    protected int $exchange_value;

    /**
     * Summary of credit_watt
     * @return void
     */
    public function credit_watt(Watt $watt) {
        foreach ($this->user_watt_exchange as $key => $user_credit) {
            if ($user_credit["userId"] == $this->user->id) {
                $this->user_watt_exchange[$key]['credits'] ++;
                $this->exchange_value = $this->user_watt_exchange[$key]['credits'];
            }
        }
    }

    /**
     * Summary of debit_watt
     * @return void
     */
    public function debit_watt(Watt $watt) {
        foreach ($this->user_watt_exchange as $key => $user_credit) {
            if ($user_credit["userId"] == $this->user->id) {
                if($this->user_watt_exchange[$key]['credits'] < 1) {
                    echo 'User id '. $this->user->id . ' does not have enough credits';
                    return;
                }
                $this->user_watt_exchange[$key]['credits'] --;
                $this->exchange_value = $this->user_watt_exchange[$key]['credits'];
            }
        }
    }

    /**å
     * Summary of __construct
     * @param User $user
     */
    public function __construct(protected User $user)
    {
        $this->exchange_value = $this->credit_check() ?? 0;
    }

    /**
     * Summary of get_exchange
     * @return array{credits: int, userId: int|int[]|null}
     */
    protected function get_exchange(): array|NULL {
        foreach ($this->user_watt_exchange as $user_credit) {
            if ($user_credit["userId"] == $this->user->id) {
                return $user_credit;
            }
        }
        return NULL;
    }
    
    /**
     * Get the credit for the user.
     * @return int|null
     */
    public function credit_check() {
        foreach ($this->user_watt_exchange as $user_credit) {
            if ($user_credit["userId"] == $this->user->id) {
                return $user_credit["credits"];
            }
        }
        return NULL;
    }

    /**
     * Calculate the total credits the user has in the exchange.
     * @return int|null
     */
    public function calculate() {
        try{
            $credit_check = $this->credit_check();
            return $credit_check;
        } catch (Exception $e) {
            print_r($e->getMessage()  . PHP_EOL);
        }
    }

    /**
     * Simply printout of the user id and the current amount of credits they have in the exchange.
     * @return void
     */
    public function display_user_credits() {
        print_r("UserId ". $this->user->id . " has " . $this->exchange_value . " credits." . PHP_EOL);
    }
}

/**
 * Calculations around Watt and current wattage.
 */
class Watt {

    /**
     * Summary of kilowatt_value
     * @var float
     */
    protected float $kilowatt_value = 0.1648;

    protected int $current_wattage = 0;

    protected float $volts = 13;

    protected float $amps = 0;

    /**
     * Summary of __construct
     * @param User $user
     */
    public function __construct() {
    }

    public function set_current_wattage() {
        $this->current_wattage = $this->volts * $this->amps;
    }

    // /**
    //  * Summary of available wattage.
    //  * @return void
    //  */
    // public function available() {}

    /**
     * Maximum watts available as determined by Amp Hours. 
     * @param float $volts
     * @param float $amps
     * @return void
     */
    public function current_maximum_hourly_watts(float $volts, float $amps) {

    }

    /**
     * Minimum batter wattage. May not use this.
     * @return int
     */
    public function minimum(): int {
        return 0;
    }

    /**
     * A watt value. Kilowatt / 1000.
     * @return float|int
     */
    public function value(): float {
        return $this->kilowatt_value / 1000;
    }

    /**
     * Current cost of a kilowatt. Using the national average.
     * @return float
     */
    private function kilowatt_value(): float {
        return $this->kilowatt_value;
    }
}

/**
 * Class for battery identification, current voltage, remaining amp hours, draw.
 */
class Battery extends Watt {

    /**
     * Fill in for Database. This would normally be a table with battery information.
     * @var array
     */
    CONST BATTERIES = [
        ["id" => "123abc", "volts" => 13.5, "currentAmps" => 10, "ampHours" => 100], 
        ["id" => "123def", "volts" => 13.7, "currentAmps" => 0,  "ampHours" => 100], 
    ];

    /**
     * Summary of battery
     * @var array
     */
    public array $battery = [];

    /**
     * Summary of __construct.
     * Battery Id.
     * @param string $id
     */
    public function __construct(public string $id) {
    }

    /**
     * Get battery based off of id.
     * @return void
     */
    public function get_battery() {
        foreach (self::BATTERIES as $battery) {
            if ($battery["id"] == $this->id) {
                $this->battery = $battery;
            }
        }
    }

    /**
     * Get ids of batteries. We may not need this.
     * @return array<int|string|null>
     */
    public static function get_ids() {
        foreach (self::BATTERIES as $subArray) {
            if (is_array($subArray)) {
                $firstKeys[] = array_key_first($subArray);
            }
        }

        return $firstKeys;
    }

    /**
     * Get baterries array. Normally this would be from a database.
     * @return array
     */
    public static function get_batteries() {
        return self::BATTERIES;
    }
}

$val = $argv[1] ?? 0;

$user = new User(id: $val)->get();

$user?->display_user();
$exchange = $user?->exchange();

$watt = new Watt;

$exchange->display_user_credits();
$exchange->credit_watt($watt);
$exchange->display_user_credits();
$exchange->debit_watt($watt);
$exchange->debit_watt($watt);
$exchange->display_user_credits();

$batteries = Battery::get_batteries();
$battery = new Battery(id: $batteries[1]['id']);