<?php

namespace App\Admin\Controllers;

use App\ApplicationType;
use App\Area;
use App\Http\Controllers\Controller;
use App\ProductAreaPreset;
use App\UseCases\UpdatePresetAreas;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Input;

class AreaPresetsController extends Controller
{
    private $updateAction;
    public function __construct(UpdatePresetAreas $updateAction)
    {
        $this->updateAction = $updateAction;
    }

    use HasResourceActions;

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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductAreaPreset);

        $grid->id('Id');
        $grid->name('Name');

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
        $show = new Show(new ProductAreaPreset($id));

        $show->id('Id');
        $show->name('Name');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ProductAreaPreset());
        $form->text("name","Название шаблона");
        $form->groupedMultipleSelect('areas')->options($this->getAreasGroupedByApplicationType());

        $form->saved(function(Form $form){
            $this->updateAction->execute($form->model(), new Collection(
                    array_filter(Input::get('areas'), function(?int $item){
                        return !is_null($item);
                    })
                )
            );
        });

        $form->submitted(function(Form $form){
            $form->ignore('areas');
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
