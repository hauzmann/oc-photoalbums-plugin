<?php namespace Graker\PhotoAlbums\Components;

use Cms\Classes\ComponentBase;
use Cms\Classes\Page;
use Graker\PhotoAlbums\Models\Photo as PhotoModel;

class Photo extends ComponentBase
{

  public $photo;

  public function componentDetails()
  {
    return [
      'name'        => 'Photo',
      'description' => 'Single photo component'
    ];
  }

  /**
   *
   * Properties of component
   *
   * @return array
   */
  public function defineProperties()
  {
    return [
      'id' => [
        'title'       => 'ID',
        'description' => 'URL id parameter',
        'default'     => '{{ :id }}',
        'type'        => 'string'
      ],
      'albumPage' => [
        'title'       => 'Album page',
        'description' => 'Page used to display photo albums',
        'type'        => 'dropdown',
        'default'     => 'photoalbums/album',
      ],
    ];
  }


  /**
   *
   * Returns pages list for album page select box setting
   *
   * @return mixed
   */
  public function getAlbumPageOptions() {
    return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
  }


  /**
   * Loads photo on onRun event
   */
  public function onRun() {
    $this->photo = $this->loadPhoto();
  }


  /**
   *
   * Loads photo to be displayed in this component
   *
   * @return PhotoModel
   */
  protected function loadPhoto() {
    $id = $this->property('id');
    $photo = PhotoModel::where('id', $id)
      ->with('image')
      ->with('album')
      ->first();

    // set url so we can have back link to the parent album
    $photo->album->url = $photo->album->setUrl($this->property('albumPage'), $this->controller);
    
    return $photo;
  }

}
