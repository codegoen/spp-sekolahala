<?php

namespace Modules\Payment\Repository\Eloquent;

use Modules\Payment\Entities\Payment;
use Modules\Payment\Repository\PaymentRepository;

class PaymentEloquent implements PaymentRepository
{
    /** @var Payment */
    protected $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get all payment
     *
     * @return object|null
     */
    public function all()
    {
        return $this->payment->all();
    }

    /**
     * Sum payment where bill
     *
     * @param string $id
     * @return int|null
     */
    public function sumPayment(string $id): ?int
    {
        return $this->payment->where('bill_id', $id)->sum('pay');
    }

    /**
     * Delete payment
     *
     * @return boolean
     */
    public function delete(string $id): bool
    {
        return $this->payment->whereId($id)->first()->delete() ? true : false;
    }
}
