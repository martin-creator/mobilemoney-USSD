<?php 
    class User{
        protected $name;
        protected $phone;
        protected $pin;
        protected $balance;


        function __construct($phone)
        {
            $this->phone = $phone;
        }

        //setter and getters
        public function setName($name){
            $this->name = $name;
        }

        public function getName(){
            return $this->name;
        }

        public function getPhone(){
            $this->phone;
        }

        public function setPin($pin){
            $this->pin = $pin;
        }

        public function getPin(){
            return $this->pin;
        }

        public function setBalance($balance){
            $this->balance = $balance;
        }

        public function getBalance(){
            return $this->balance;
        }

        public function register($pdo){

        }

        public function isUserRegistered($pdo){
            
        }

        public function readName($pdo){
            
        }

        public function readUserId($pdo){
            
        }

        public function connectPin($pdo){
            
        }

        public function checkBalance($pdo){
            
        }

    }






?>