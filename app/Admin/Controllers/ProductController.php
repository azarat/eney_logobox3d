<?php

namespace App\Admin\Controllers;

use App\ApplicationType;
use App\Area;
use App\Http\Controllers\Controller;
use App\Product;
use App\ProductAreaPreset;
use App\UseCases\CopyPresetToProduct;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Filter;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Widgets\Box;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HasResourceActions;

    private $copyPresetAction;

    public function __construct(CopyPresetToProduct $copyPresetAction)
    {
        $this->copyPresetAction = $copyPresetAction;
    }

    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header('Index')
            ->description('description')
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header('Detail')
            ->description('description')
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header('Edit')
            ->description('description')
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    public function store(Request $request)
    {
        $request->request->remove('product_area_preset_id');

        return $this->form()->store();
    }

    public function update(Request $request, $id)
    {
        $request->request->remove('product_area_preset_id');

        return $this->form()->update($id);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Product());

        $grid->filter(function(Filter $filter){
            $filter->disableIdFilter();
            $filter->like('model', 'Модель');
        });

        $grid->id('Id');
        $grid->processed('Processed');
        $grid->category('Category');
        $grid->column('model','Model');
        $grid->model_id_2d('Model id 2d');
        $grid->model_id_3d('Model id 3d');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

        $grid->footer(function ($query) {
            $view  = view('admin.product.import');

            return new Box('Import', $view);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Area::findOrFail($id));

        $show->id('Id');
        $show->processed('Processed');
        $show->category('category');
        $show->model('Model');
        $show->model_id_2d('Model id 2d');
        $show->model_id_3d('Model id 3d');
        $show->created_at('Created at');
        $show->updated_at('Updated at');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Product);

        $form->switch('processed', 'Processed');
        $form->text('model', 'Model')->rules('required');
        $form->text('category','Category')->rules('required|regex:/^\d+$/');
        $form->image('image', 'Image');
        $form->select('product_area_preset_id', 'Preset')->options(ProductAreaPreset::all()->reduce(
            function(array $acc,ProductAreaPreset $preset){
              $acc[$preset->id] = $preset->name;
              return $acc;
            },
            []
        ));

        $form->groupedMultipleSelect('areas')->options($this->getAreasGroupedByApplicationType());
        $form->saved(function(Form $form){
            $product = $form->model();
            $preset = ProductAreaPreset::find($product['product_area_preset_id']);
            if(is_null($preset)) return;
            $this->copyPresetAction->execute($product, $preset);
        });
        return $form;
    }

    private function getAreasGroupedByApplicationType(){
        return ApplicationType::with('areas')->get()->reduce(function(array $acc, ApplicationType $type){
            $acc[$type->getDefaultTranslatedName()] = $type->areas->reduce(function(array $acc, Area $area){
                $acc[$area->id] = $area->getDefaultTranslatedName();
                return $acc;
            },[]);
            return $acc;
        },[]);
    }
}
