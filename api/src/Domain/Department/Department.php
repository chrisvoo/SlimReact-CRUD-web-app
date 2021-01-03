<?php

namespace App\Domain\Department;

use JsonSerializable;


class Department implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * Department's name.
     *
     * @var string|null
     */
    private $name;

    /**
     * Constructor with all the params.
     *
     * @param array  $params
     */
    public function __construct($params = [])
    {
        $this->setId($params["id"] ?? null);
        $this->setName($params["name"] ?? null);
    }

    /**
     * Returns the primary key.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the primary key.
     *
     * @param int|null $id The PK.
     * @return Department
     */
    public function setId(?int $id): Department
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            "name" => $this->name
        ];
    }

    /**
     * Returns the name.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets the first name.
     *
     * @param string|null $name The name.
     *
     * @return Department
     */
    public function setName(?string $name): Department
    {
        $this->name = $name;
        return $this;
    }
}