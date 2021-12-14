<?php

namespace App\Admin\Controllers;

use App\ApplicationType;
use App\Http\Controllers\Controller;
use App\ValueObjects\Translation;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Input;

class ApplicationTypeController extends Controller
{
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
        $grid = new Grid(new ApplicationType);

        $grid->id('Id');
        $grid->status('Status');
        $grid->code('Code');
        $grid->time('Time');
        $grid->image('Image');
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
        $show = new Show(ApplicationType::findOrFail($id));

        $show->id('Id');
        $show->status('Status');
        $show->created_at('Created at');
        $show->updated_at('Updated at');
        $show->code('Code');
        $show->time('Time');
        $show->image('Image');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ApplicationType);
        $form->tab('ApplicationType',function($form) {
            $form->switch('status', 'Status');
            $form->text('code', 'Code')->rules('required|min:2|max:2');
            $form->text('time', 'Time')->rules('required|min:2|max:2');
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
