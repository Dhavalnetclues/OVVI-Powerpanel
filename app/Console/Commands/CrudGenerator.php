<?php

namespace App\Console\Commands;

use Artisan;
use File;
use Illuminate\Console\Command;
use Schema;

class CrudGenerator extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generator
    {name : Class (singular) for example User}
    {data : Json Data from GUI}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create CRUD operations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name      = $this->argument('name');
        $jsonData  = json_decode($this->argument('data'));
        $modelName = preg_replace('/\s+/', '', ucwords($name)); 

        $this->migration($modelName, $jsonData);
        $this->model($modelName, $jsonData);
        $this->controller($modelName, $jsonData);
        $this->view($modelName, $jsonData);
        //$this->createRoutes($name);

        $this->info(true);
    }

    protected function createRoutes($name)
    {

        File::append(base_path('routes/web.php'), "Route::group(['namespace' => 'Powerpanel', 'middleware' => ['auth']], function () {\n");
        $this->getListRoutes($name);
        $this->getAddRoutes($name);
        $this->getEditRoutes($name);
        File::append(base_path('routes/web.php'), "});\n");

    }

    protected function getListRoutes($name)
    {
        File::append(base_path('routes/web.php'), "Route::get('/powerpanel/" . str_plural(strtolower($name)) . "', ['namespace' => 'Powerpanel','uses' => '" . $name . "Controller@index'])->name('powerpanel." . str_plural(strtolower($name)) . ".index');\n");
        File::append(base_path('routes/web.php'), "Route::get('/powerpanel/" . str_plural(strtolower($name)) . "/get_list', ['namespace' => 'Powerpanel','uses' => '" . $name . "Controller@get_list'])->name('powerpanel." . str_plural(strtolower($name)) . ".get_list');\n");
    }

    protected function getAddRoutes($name)
    {
        File::append(base_path('routes/web.php'), "Route::get('/powerpanel/" . str_plural(strtolower($name)) . "/add', ['namespace' => 'Powerpanel','uses' => '" . $name . "Controller@edit'])->name('powerpanel." . str_plural(strtolower($name)) . ".add');\n");
        File::append(base_path('routes/web.php'), "Route::get('/powerpanel/" . str_plural(strtolower($name)) . "/add', ['namespace' => 'Powerpanel','uses' => '" . $name . "Controller@handlePost'])->name('powerpanel." . str_plural(strtolower($name)) . ".handleAddPost');\n");
    }

    protected function getEditRoutes($name)
    {
        File::append(base_path('routes/web.php'), "Route::get('/powerpanel/" . str_plural(strtolower($name)) . "/{alias}/edit', ['namespace' => 'Powerpanel','uses' => '" . $name . "Controller@edit'])->name('powerpanel." . str_plural(strtolower($name)) . ".edit');\n");
        File::append(base_path('routes/web.php'), "Route::get('/powerpanel/" . str_plural(strtolower($name)) . "/{alias}/edit', ['namespace' => 'Powerpanel','uses' => '" . $name . "Controller@handlePost'])->name('powerpanel." . str_plural(strtolower($name)) . ".handleEditPost');\n");
    }

    protected function getStub($type)
    {
        return file_get_contents(resource_path("$type.stub"));
    }

    protected function getFieldStub($fieldType)
    {
        return file_get_contents(resource_path("stubs/fields/$fieldType.stub"));
    }

    protected function model($name, $formData)
    {
        $fieldsArr           = array();
        $searchableFieldsArr = array();
        $i                   = 0;
        foreach ($formData->fields as $key => $value) {
            if (isset($value->name)) {
                $fieldsArr[$key] = "'" . $value->name . "'";
                if ($value->searchable == true && $value->inForm == true) {
                    $searchableFieldsArr[$i] = '$data = $query->orWhere("' . $value->name . '", "like", "%" . $filterArr["searchFilter"] . "%");';
                    $i++;
                }
            }
        }

        $modelTemplate = str_replace(
            [
                '{{modelName}}',
                '{{ tableName }}',
                '{{ fields }}',
                '{{ date }}',
                '{{ searchableFields  }}',
            ],
            [
                $name,
                strtolower(str_slug($formData->tableName, '_')),
                implode(',', $fieldsArr),
                date('d-m-Y'),
                implode("\n", $searchableFieldsArr),
            ],
            $this->getStub('stubs/Model'));

        file_put_contents(app_path("/{$name}.php"), $modelTemplate);

    }

    protected function controller($name, $formData)
    {

        $fieldsArr               = array();
        $arrForAddFields         = array();
        $serverSideValidationArr = array();
        $advanceLogField         = array();
        $advanceLogFieldVal      = array();
        $displayOrderFieldVal    = '';
        $publisUnpublishVal      = '';
        $swapOrderAdd            = '';
        $swapOrderEdit           = '';

        $ignoreFields = array('id', 'chrDelete', 'created_at', 'updated_at');
        $i            = 0;

        foreach ($formData->fields as $key => $value) {
            if ($value->inForm == true) {
                if (isset($value->name)) {
                    if (!in_array($value->name, $ignoreFields)) {
                        if ($formData->options->displayOrder == true && $value->name == "intDisplayOrder") {
                            $displayOrderFieldVal = $this->getStub('stubs/display-order/list');
                            $swapOrderAdd         = '$fieldsArr["' . $value->name . '"] = self::swap_order_add($postArr["' . $value->name . '"]);';
                            $swapOrderEdit        = 'self::swap_order_edit($postArr["intDisplayOrder"], $id);';
                            $fieldsArr[$i]        = '$orderArrow';
                        } else if ($value->name == "chrPublish") {
                            $arrForAddFields[$i] = '$fieldsArr["' . $value->name . '"] = trim($postArr["' . $value->name . '"]);';
                        } else {
                            $fieldsArr[$i]       = '$value->' . $value->name;
                            $arrForAddFields[$i] = '$fieldsArr["' . $value->name . '"] = trim($postArr["' . $value->name . '"]);';
                        }

                        if (isset($value->validations) && $value->validations != "") {
                            $serverSideValidationArr[$i] = '"' . $value->name . '" => "' . $value->validations . '"';
                        }

                        $advanceLogField[$i]    = "<th>" . $value->title . "</th>";
                        $advanceLogFieldVal[$i] = '<td>' . "'." . '$data->' . $value->name . ".'" . '</td>';
                        $i++;
                    }

                    if ($value->name == "chrPublish") {
                        $publisUnpublishVal = str_replace(
                            ['{{ moduleSlug }}'],
                            [strtolower(str_slug($formData->moduleTitle, '-'))],
                            $this->getStub('stubs/publish-unpublish/list')
                        );
                    }
                }
            }
        }

        $controllerTemplate = str_replace(
            [
                '{{moduleTitle}}',
                '{{modelName}}',
                '{{moduleSlug}}',
                '{{ fields }}',
                '{{ date }}',
                '{{ addFields }}',
                '{{ serverSideValidationRules }}',
                '{{  advanceLogField }}',
                '{{  advanceLogFieldVal }}',
                '{{ displayOrderFieldVal }}',
                '{{ swapOrderEdit }}',
                '{{ swapOrderAdd }}',
                '{{ publishUnPublish }}',
            ],
            [
                $formData->moduleTitle,
                $name,
                strtolower(str_slug($formData->moduleTitle, '-')),
                implode(", \n", $fieldsArr),
                date('d-m-Y'),
                implode("\n", $arrForAddFields),
                implode(", \n", $serverSideValidationArr),
                implode("\n", $advanceLogField),
                implode("\n", $advanceLogFieldVal),
                $displayOrderFieldVal,
                $swapOrderEdit,
                $swapOrderAdd,
                $publisUnpublishVal,
            ],
            $this->getStub('stubs/Controller')
        );

        file_put_contents(app_path("/Http/Controllers/Powerpanel/{$name}Controller.php"), $controllerTemplate);

    }

    protected function request($name)
    {
        $requestTemplate = str_replace(
            ['{{modelName}}'],
            [$name],
            $this->getStub('stubs/Request')
        );

        if (!file_exists($path = app_path('/Http/Requests'))) {
            mkdir($path, 0777, true);
        }

        file_put_contents(app_path("/Http/Requests/{$name}Request.php"), $requestTemplate);
    }

    protected function migration($name, $formData)
    {

        $fieldsArr    = array();
        $ignoreFields = array('id', 'chrPublish', 'chrDelete', 'created_at', 'updated_at');
        foreach ($formData->fields as $key => $value) {
            if (isset($value->name)) {
                if (!in_array($value->name, $ignoreFields)) {
                    if ($value->dbType == "string") {
                        $length = ',255';
                    } else if ($value->dbType == "char") {
                        $length = ',50';
                    } else {
                        $length = '';
                    }

                    $fieldsArr[$key] = '$table->' . $value->dbType . '("' . $value->name . '"' . $length . ')->collation("utf8_general_ci");';
                }
            }
        }

        $requestTemplate = str_replace(
            [
                '{{modelName}}',
                '{{tableName}}',
                '{{fields}}',
            ], [
                $name,
                strtolower(str_slug($formData->tableName, '_')),
                implode("\n", $fieldsArr),
            ], $this->getStub('stubs/Migration'));

        if (!file_exists($path = base_path('/database/migrations'))) {
            mkdir($path, 0777, true);
        }

        if (!file_exists($gen_path = base_path('/database/migrations/generated'))) {
            mkdir($gen_path, 0777, true);
        }

        file_put_contents(base_path("/database/migrations/generated/" . date('Y_m_d_His') . "_create_" . strtolower($name) . "_table.php"), $requestTemplate);

        Schema::dropIfExists(strtolower(str_slug($formData->tableName, '_')));
        Artisan::call('migrate', ['--path' => '/database/migrations/generated/']);

    }

    protected function view($name, $formData)
    {

        if (!file_exists($path = base_path('/resources/views/powerpanel'))) {
            mkdir($path, 0777, true);
        }

        if (!file_exists($viewPath = base_path('/resources/views/powerpanel/' . strtolower(str_slug($formData->moduleTitle, '-'))))) {
            mkdir($viewPath, 0777, true);
        }

        $this->createIndexView($name, $formData);
        $this->createActionsView($name, $formData);

    }

    protected function createIndexView($name, $formData)
    {
        $fieldsArrForIndex        = array();
        $fieldsArrForDatatable    = array();
        $ignoreFields             = array('id', 'chrPublish', 'chrDelete', 'created_at', 'updated_at');
        $ignoreFieldsForDatatable = array('chrPublish', 'chrDelete', 'created_at', 'updated_at');
        $i                        = 0;

        foreach ($formData->fields as $key => $value) {
            if ($value->inForm == true) {
                if (isset($value->name)) {
                    if (!in_array($value->name, $ignoreFields)) {
                        $fieldsArrForIndex[$key] = '<th align="left">' . $value->title . '</th>';
                    }

                    if (!in_array($value->name, $ignoreFieldsForDatatable)) {
                        if ($i == 1) {
                            $sortSetting = '"name": "' . $value->name . '"';
                        } else {
                            $sortSetting = '"bSortable": false';
                        }

                        $fieldsArrForDatatable[$i] = '{"data": ' . $i . ',className: "text-left",' . $sortSetting . '}';
                        $i++;
                    }
                }
            }
        }

        $lastKey                               = count($fieldsArrForDatatable);
        $fieldsArrForDatatable[$lastKey]       = '{"data": ' . $lastKey . ',className: "text-right publish_switch","bSortable": false}';
        $fieldsArrForDatatable[($lastKey + 1)] = '{"data": ' . ($lastKey + 1) . ',className: "text-right","bSortable": false}';

        $requestIndexTemplate = str_replace(
            [
                '{{moduleTitle}}',
                '{{modelName}}',
                '{{tableName}}',
                '{{moduleSlug}}',
                '{{fieldsForIndex}}',
                '{{fieldsForDatatable}}',
            ], [
                $formData->moduleTitle,
                $name,
                strtolower(str_slug($formData->tableName, '_')),
                strtolower(str_slug($formData->moduleTitle, '-')),
                implode("\n", $fieldsArrForIndex),
                implode(", \n", $fieldsArrForDatatable),
            ], $this->getStub('stubs/Index'));

        file_put_contents(base_path("/resources/views/powerpanel/" . strtolower(str_slug($formData->moduleTitle, '-')) . '/' . "index.blade.php"), $requestIndexTemplate);
    }

    protected function createActionsView($name, $formData)
    {
        $inputsArr             = array();
        $displayOrderInput     = '';
        $jqueryValidation = '';
        $validationRules = array();  
        $validationMessages = array();  
        $publishUnpublishInput = '';
        $ignoreFields          = array('id', 'chrDelete', 'created_at', 'updated_at');
        $i                     = 0;

        foreach ($formData->fields as $key => $value) 
        {
            if ($value->inForm == true) 
            {
                if (isset($value->name)) 
                {
                    if (!in_array($value->name, $ignoreFields)) 
                    {
                        if ($formData->options->displayOrder == true && $value->name == "intDisplayOrder") {
                            $displayOrderInput = $this->getDisplayOrderInput($value);
                        } else if ($value->name == "chrPublish") {
                            $publishUnpublishInput = $this->getPunlishUnpublishIntput($value);
                        } else {
                            $inputsArr[$i] = $this->getInputHtml($value);
                        }

                        if($value->validations == "min:5")
                        {
                          $validationRules[$i] = ''.$value->name.': { minlength:5 }';
                        }else if($value->validations == "numeric") {
                          $validationRules[$i] = ''.$value->name.': { number:true }';  
                        }else if($value->validations == ""){
                           $validationRules[$i] = ''.$value->name.': { required:false }';     
                        }else{
                          $validationRules[$i] = ''.$value->name.': { '.$value->validations.':true }';
                        }
                        
                        if($value->validations == "required")
                        {
                          $validationMessages[$i] = ''.$value->name.':{'.$value->validations.':'."'".$value->title." is required'".'}';
                        }  

                        $jqueryValidation = str_replace([
                            '{{ fieldTitle }}',
                            '{{ fieldName }}',
                            '{{ fieldType }}',
                            '{{ fieldValidation }}',
                            '{{ modelName }}',
                            '{{ validationRules }}',
                            '{{ validationMessages }}'
                        ], [
                            $value->title,
                            $value->name,
                            $value->htmlType,
                            $value->validations,
                            $name,
                            implode(", \n", $validationRules),
                            implode(", \n", $validationMessages),
                        ], $this->getStub('stubs/JqueryValidation'));
                        
                        $i++;
                    }
                }
            }
        }

        file_put_contents(base_path("/public_html/resources/pages/scripts/".strtolower(str_slug($formData->moduleTitle, '-'))."-validations.js"), $jqueryValidation);            

        $requestActionTemplate = str_replace(
            [
                '{{moduleTitle}}',
                '{{modelName}}',
                '{{tableName}}',
                '{{moduleSlug}}',
                '{{ inputs }}',
                '{{ displayOrder }}',
                '{{ publishUnpublish  }}',

            ], [
                $formData->moduleTitle,
                $name,
                strtolower(str_slug($formData->tableName, '_')),
                strtolower(str_slug($formData->moduleTitle, '-')),
                implode("\n", $inputsArr),
                $displayOrderInput,
                $publishUnpublishInput,
            ], $this->getStub('stubs/Action'));

        file_put_contents(base_path("/resources/views/powerpanel/" . strtolower(str_slug($formData->moduleTitle, '-')) . '/' . "actions.blade.php"), $requestActionTemplate);
    }

    protected function getInputHtml($field)
    {
        $response = false;
        $response = str_replace([
            '{{ fieldTitle }}',
            '{{ fieldName }}',
            '{{ fieldType }}',
            '{{ fieldValidation }}',
        ], [
            $field->title,
            $field->name,
            $field->htmlType,
            $field->validations,
        ], $this->getStub('stubs/fields/' . $field->htmlType));
        return $response;
    }

    protected function getPunlishUnpublishIntput($field)
    {
        $response = false;
        $response = str_replace([
            '{{ fieldTitle }}',
            '{{ fieldName }}',
            '{{ fieldType }}',
            '{{ fieldValidation }}',
        ], [
            $field->title,
            $field->name,
            $field->htmlType,
            $field->validations,
        ], $this->getStub('stubs/publish-unpublish/actions'));

        return $response;
    }

    protected function getDisplayOrderInput($field)
    {
        $response = false;
        $response = str_replace([
            '{{ fieldTitle }}',
            '{{ fieldName }}',
            '{{ fieldType }}',
            '{{ fieldValidation }}',
        ], [
            $field->title,
            $field->name,
            $field->htmlType,
            $field->validations,
        ], $this->getStub('stubs/display-order/actions'));
        return $response;
    }
    
}
