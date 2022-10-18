<?php
namespace teik\Theme\Blocks;

use teik\Theme\Traits\Singleton;

class FileList extends AbstractBlock
{
  use Singleton;

  public $name = 'file-list';
  public $title = 'Lista plików';
}