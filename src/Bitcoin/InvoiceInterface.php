<?php
Namespace Yellow\Bitcoin;
Interface InvoiceInterface
{
    public function createInvoice($currency, $amount);

    public function checkInvoiceStatus($id);
}