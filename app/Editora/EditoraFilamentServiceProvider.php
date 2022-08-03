<?php

namespace App\Editora;

use Filament\PluginServiceProvider;
use App\Filament\Resources\InstanceResource;
use Spatie\LaravelPackageTools\Package;
use DB;
use Str;

class EditoraFilamentServiceProvider extends PluginServiceProvider
{
  protected array $resources = [];

  public function configurePackage(Package $package): void
  {
      $package->name('editora-package');
  }

  function __construct ($app)
  {
    parent::__construct($app);
    return;
    
    $rows=DB::select('select * from omp_classes limit 3');
    foreach ($rows as $row)
    {
      $className=Str::of("Editora ".$row->tag)->camel();

      $strPageClasses="
      namespace App\Editora;

      use App\Filament\Resources\InstanceResource;
      use Filament\Pages\Actions;
      use Filament\Resources\Pages\ListRecords;
      use Filament\Resources\Pages\EditRecord;
      use Filament\Resources\Pages\CreateRecord;
      
      class List".Str::of($className)->plural()." extends ListRecords
      {
          protected static string \$resource = $className::class;
      }
      
      class Edit$className extends EditRecord
      {
          protected static string \$resource = $className::class;
      }

      class Create$className extends CreateRecord
      {
        protected static string \$resource = $className::class;
      }
      
      ";
      eval($strPageClasses);

      $strClass="
      namespace App\Editora;
      class $className extends InstanceResource 
        {
          protected static int \$classID=".(int)$row->id.";
          protected static ?string \$modelLabel = '".Str::of($row->tag)->camel()."';

          public static function getPages(): array
          {
              return [
                  'index' => App\Editora\List".Str::of($className)->plural()."::route('/'),
                  'create' => App\Editora\Create$className::route('/create'),
                  'edit' => App\Editora\Edit$className::route('/{record}/edit'),
              ];
          } 
        }\n";
      eval($strClass);
      $this->resources[]=$className;
    }

  }

}