<?php

namespace App\Http\Controllers\Voyager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use TCG\Voyager\Database\Schema\SchemaManager;
use TCG\Voyager\Events\BreadDataAdded;
use TCG\Voyager\Events\BreadDataDeleted;
use TCG\Voyager\Events\BreadDataUpdated;
use TCG\Voyager\Events\BreadImagesDeleted;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Http\Controllers\Traits\BreadRelationshipParser;
use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use App\Product;
use App\Category;
use App\CategoryProduct;
use App\Order;

class OrdersController extends VoyagerBaseController
{
  public function show(Request $request, $id)
  {
      $slug = $this->getSlug($request);

      $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

      $relationships = $this->getRelationships($dataType);
      if (strlen($dataType->model_name) != 0) {
          $model = app($dataType->model_name);
          $dataTypeContent = call_user_func([$model->with($relationships), 'findOrFail'], $id);
      } else {
          // If Model doest exist, get data from table name
          $dataTypeContent = DB::table($dataType->name)->where('id', $id)->first();
      }

      // Replace relationships' keys for labels and create READ links if a slug is provided.
      $dataTypeContent = $this->resolveRelations($dataTypeContent, $dataType, true);

      // If a column has a relationship associated with it, we do not want to show that field
      $this->removeRelationshipField($dataType, 'read');

      // Check permission
      $this->authorize('read', $dataTypeContent);

      // Check if BREAD is Translatable
      $isModelTranslatable = is_bread_translatable($dataTypeContent);

      $view = 'voyager::bread.read';

      if (view()->exists("voyager::$slug.read")) {
          $view = "voyager::$slug.read";
      }

      $order = Order::find($id);
      $products = $order->products;
      return Voyager::view($view, compact('dataType', 'dataTypeContent', 'isModelTranslatable', 'products'));
  }

}
