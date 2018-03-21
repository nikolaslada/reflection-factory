<?php
declare(strict_types=1);

/**
 * This file is a part of the Reflection Factory library.
 * Copyright (c) 2018 Nikolas Lada.
 * @author Nikolas Lada <nikolas.lada@gmail.com>
 */

namespace NikolasLada\ReflectionFactory\Tests\Domain;

class Article {

  /** @var int */
  private $id;
  /** @var string */
  private $title;
  /** @var string */
  private $content;
  /** @var \DateTime */
  private $created;
  /** @var null|\DateTime */
  private $updated;
  /** @var string */
  private $authorName;
  /** @var string */
  private $authorLink;

  public function __construct(int $id, string $title, string $content, \DateTime $created, ?\DateTime $updated, string $authorName, string $authorLink) {
    $this->id = $id;
    $this->title = $title;
    $this->content = $content;
    $this->created = $created;
    $this->updated = $updated;
    $this->authorName = $authorName;
    $this->authorLink = $authorLink;
  }

  public function getId(): int {
    return $this->id;
  }

  public function getTitle(): string {
    return $this->title;
  }

  public function getContent(): string {
    return $this->content;
  }

  public function getCreated(): \DateTime {
    return $this->created;
  }

  public function getUpdated(): \DateTime {
    return $this->updated;
  }

  public function getAuthorName(): string {
    return $this->authorName;
  }

  public function getAuthorLink(): string {
    return $this->authorLink;
  }

}
