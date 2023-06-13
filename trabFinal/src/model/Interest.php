<?php

class Interest
{

    protected int $id;
    protected string $message;
    protected DateTime $createdAt;
    protected string $contact;
    protected int $product_id;

    public function __construct(int $id, string $message, DateTime $createdAt, string $contact, int $product_id)
    {
        $this->id = $id;
        $this->message = $message;
        $this->createdAt = $createdAt;
        $this->contact = $contact;
        $this->product_id = $product_id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getContact(): string
    {
        return $this->contact;
    }

    public function getProductId(): int
    {
        return $this->product_id;
    }


}