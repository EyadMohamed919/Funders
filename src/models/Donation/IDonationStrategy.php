<?php
interface IDonationStrategy{
    public function getDonationID();
    public function getPostID();
    public function getCreatedAt();
    public function getStatus();
    public function getDonationType();
    public function getUserID();
    public function processDonation($data);
    public function getDonationByDonationID($donationID);
}