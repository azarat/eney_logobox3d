<?php

namespace App\Admin\Controllers;

use App\ApplicationType;
use App\Area;
use App\Http\Controllers\Controller;

use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Input;

class AreaController extends Controller
{
    use HasResourceActions;

    const NO_LOCALE = 'Нет перевода';

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
        $grid = new Grid(new Area);

        $grid->id('Id');
        $grid->status('Status');
        $grid->code('Code');
        $grid->application_type_id('Application type')->display(function($applicationId){
            return ApplicationType::find($applicationId)
                    ->getDefaultTranslatedName() ?? self::NO_LOCALE;
        });
        $grid->image('Image');
        $grid->prepare_price('Prepare price');
        $grid->print_price('Print price');
        $grid->sticking_price('Sticking price');
        $grid->roasting_price('Roast price');
        $grid->kx('Kx');
        $grid->kz('Kz');
        $grid->max_colors('Max colors');
        $grid->max_copy('Max copy');
        $grid->created_at('Created at');
        $grid->updated_at('Updated at');

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
        $show->status('Status');
        $show->code('Code');
        $show->application_type_id('Application type')->display(function($applicationId){
            return ApplicationType::find($applicationId)
                ->getDefaultTranslatedName() ?? self::NO_LOCALE;
        });
        $show->image('Image');
        $show->prepare_price('Prepare price');
        $show->print_pirce('Print price');
        $show->sticking_price('Sticking price');
        $show->roasting_price('Roast price');
        $show->kx('Kx');
        $show->kz('Kz');
        $show->max_colors('Max colors');
        $show->max_copy('Max copy');
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
        $form = new Form(new Area);
        $form->tab('Area',function($form) {
            $form->switch('status', 'Status');
            $form->text('code', 'Code')->rules('required|size:4');
            $form->select('application_type_id', 'Application type')->options(
                ApplicationType::all()->reduce(function(array $acc, ApplicationType $app){
                    $acc[$app->id] = $app->getDefaultTranslatedName();
                    return $acc;
                },
                    [])
            );
            $form->currency('prepare_price','Prepare price');
            $form->currency('print_price','Print price');
            $form->currency('sticking_price','Sticking price');
            $form->currency('roasting_price','Roasting price');
            $form->number('kx','Kx');
            $form->number('kz','Kz');
            $form->number('max_colors','Max colors');
            $form->number('max_copy', 'Max copy');
            $form->image('image', 'Image');
        })
        ->tab('Переводы',function (Form $form){
            $form->translation('localization')->ignoreForm($form);
        })
        ->saved(function(Form $form){
             $model = $form->model();
             $model->translations = Input::get('translations');
             $model->save();
        });

        return $form;
    }
}
