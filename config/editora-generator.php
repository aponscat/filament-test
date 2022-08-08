<?php

use Illuminate\Support\Str;

return [

  # Folders
  'resourcesPathSuffix' => env('EDITORA_GENERATOR_RESOURCES_PATH_SUFFIX', '/Filament/Resources/'),
  'modelsPathSuffix' => env('EDITORA_GENERATOR_MODELS_PATH_SUFFIX', '/Editora/Models/Classes/Sushi/'),

  ## STUB files
  'modelStub' => env('EDITORA_GENERATOR_MODEL_STUB', '/Editora/stubs/model.stub'),
  'resourceStub' => env('EDITORA_GENERATOR_RESOURCE_STUB', '/Editora/stubs/resource.stub'),
  'createStub' => env('EDITORA_GENERATOR_CREATE_STUB',  '/Editora/stubs/create.stub'),
  'editStub' => env('EDITORA_GENERATOR_EDIT_STUB',  '/Editora/stubs/edit.stub'),
  'listStub' => env('EDITORA_GENERATOR_LIST_STUB',  '/Editora/stubs/list.stub'),
];
