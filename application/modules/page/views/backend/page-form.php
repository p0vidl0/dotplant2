<?php

use app\backend\widgets\BackendWidget;
use app\backend\components\ActiveForm;
use app\modules\page\backend\PageController;
use kartik\helpers\Html;
use kartik\icons\Icon;
use kartik\widgets\DateTimePicker;
use vova07\imperavi\Widget as ImperaviWidget;
use yii\helpers\Url;


$this->title = Yii::t('app', 'Page edit');
$this->params['breadcrumbs'][] = ['url' => ['/page/backend/index'], 'label' => Yii::t('app', 'Pages')];
if ($model->parent_id > 0) {
    $this->params['breadcrumbs'][] = [
        'url' => [
            '/page/backend/index',
            'id' => $model->parent_id,
            'parent_id' => $model->parent->parent_id
        ],
        'label' => $model->parent->breadcrumbs_label
    ];
}
$this->params['breadcrumbs'][] = $this->title;

?>

<?=app\widgets\Alert::widget(
    [
        'id' => 'alert',
    ]
);?>

<?php $form = ActiveForm::begin(['id' => 'page-form', 'type' => ActiveForm::TYPE_HORIZONTAL]); ?>

<?php $this->beginBlock('submit'); ?>
<div class="form-group no-margin">
    <?php if (!$model->isNewRecord): ?>
        <?=Html::a(
            Icon::show('eye') . Yii::t('app', 'Preview'),
            [
                '/page/page/show',
                'id' => $model->id,
            ],
            [
                'class' => 'btn btn-info',
                'target' => '_blank',
            ]
        )?>
    <?php endif; ?>
    <?=
    Html::a(
        Icon::show('arrow-circle-left') . Yii::t('app', 'Back'),
        Yii::$app->request->get('returnUrl', ['/page/backend/index']),
        ['class' => 'btn btn-danger']
    )
    ?>
    <?php if ($model->isNewRecord): ?>
        <?=Html::submitButton(
            Icon::show('save') . Yii::t('app', 'Save & Go next'),
            [
                'class' => 'btn btn-success',
                'name' => 'action',
                'value' => 'next',
            ]
        )?>
    <?php endif; ?>
    <?=Html::submitButton(
        Icon::show('save') . Yii::t('app', 'Save & Go back'),
        [
            'class' => 'btn btn-warning',
            'name' => 'action',
            'value' => 'back',
        ]
    );?>
    <?=
    Html::submitButton(
        Icon::show('save') . Yii::t('app', 'Save'),
        [
            'class' => 'btn btn-primary',
            'name' => 'action',
            'value' => 'save',
        ]
    )
    ?>
</div>
<?php $this->endBlock('submit'); ?>

<section id="widget-grid">
    <div class="row">

        <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">

            <?php BackendWidget::begin(
                ['title' => Yii::t('app', 'Page'), 'icon' => 'pencil', 'footer' => $this->blocks['submit']]
            ); ?>

            <?=
            $form->field($model, 'name')
            ?>

            <?=
            $form->field($model, 'title',
                [
                    'copyFrom'=>[
                        "#page-name",
                        "#page-h1",
                        '#page-breadcrumbs_label',
                    ]
                ]
            )
            ?>

            <?=
            $form->field($model, 'show_type')->dropDownList(
                    [
                        'show' => Yii::t('app', 'Show page'),
                        'list' => Yii::t('app', 'Page list'),
                    ]
                );
            ?>

            <?=
            $form->field(app\models\ViewObject::getByModel($model, true), 'view_id')->dropDownList(
                    app\models\View::getAllAsArray()
                );
            ?>

            <?=$form->field($model, 'content')->widget(
                ImperaviWidget::className(),
                [
                    'settings' => [
                        'replaceDivs' => false,
                        'minHeight' => 200,
                        'paragraphize' => true,
                        'pastePlainText' => true,
                        'buttonSource' => true,
                        'imageManagerJson' => Url::to(['/backend/dashboard/imperavi-images-get']),
                        'plugins' => [
                            'table',
                            'fontsize',
                            'fontfamily',
                            'fontcolor',
                            'video',
                            'imagemanager',
                        ],
                        'replaceStyles' => [],
                        'replaceTags' => [],
                        'deniedTags' => [],
                        'removeEmpty' => [],
                        'imageUpload' => Url::to(['/backend/dashboard/imperavi-image-upload']),
                    ],
                ]
            );?>

            <?=$form->field($model, 'announce')->widget(
                ImperaviWidget::className(),
                [
                    'settings' => [
                        'replaceDivs' => false,
                        'minHeight' => 200,
                        'paragraphize' => true,
                        'pastePlainText' => true,
                        'buttonSource' => true,
                        'imageManagerJson' => Url::to(['/backend/dashboard/imperavi-images-get']),
                        'plugins' => [
                            'table',
                            'fontsize',
                            'fontfamily',
                            'fontcolor',
                            'video',
                            'imagemanager',
                        ],
                        'replaceStyles' => [],
                        'replaceTags' => [],
                        'deniedTags' => [],
                        'removeEmpty' => [],
                        'imageUpload' => Url::to(['/backend/dashboard/imperavi-image-upload']),
                    ],
                ]
            );?>

            <?=$form->field($model, 'sort_order');?>

            <?=$form->field($model, 'date_added')->widget(
                DateTimePicker::classname(),
                [
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd hh:ii',
                        'todayHighlight' => true,
                        'todayBtn' => true,

                    ]
                ]
            );?>

            <?=$form->field($model, 'published')->checkbox()?>



            <?php BackendWidget::end(); ?>


            <?php
            BackendWidget::begin(
                [
                    'title' => Yii::t('app', 'Images'),
                    'icon' => 'image',
                    'footer' => $this->blocks['submit']
                ]
            ); ?>

            <div id="actions">
                <?=
                \yii\helpers\Html::tag(
                    'span',
                    Icon::show('plus') . Yii::t('app', 'Add files..'),
                    [
                        'class' => 'btn btn-success fileinput-button'
                    ]
                )?>
            </div>

            <?=\app\modules\image\widgets\ImageDropzone::widget(
                [
                    'name' => 'file',
                    'url' => ['upload'],
                    'removeUrl' => ['remove'],
                    'uploadDir' => '/theme/resources/product-images',
                    'sortable' => true,
                    'sortableOptions' => [
                        'items' => '.dz-image-preview',
                    ],
                    'objectId' => $object->id,
                    'modelId' => $model->id,
                    'htmlOptions' => [
                        'class' => 'table table-striped files',
                        'id' => 'previews',
                    ],
                    'options' => [
                        'clickable' => ".fileinput-button",
                    ],
                ]
            );?>

            <?php BackendWidget::end(); ?>
        </article>
        <article class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
            <?php BackendWidget::begin(
                ['title' => Yii::t('app', 'SEO'), 'icon' => 'search', 'footer' => $this->blocks['submit']]
            ); ?>

            <?=
            $form->field(
                $model,
                'slug',
                [
                    'makeSlug'=>[
                        "#page-title",
                        "#page-h1",
                        "#page-breadcrumbs_label"
                    ]
                ]
            )
            ?>
            <?=$form->field($model, 'slug_absolute')->checkbox()?>
            <?=
            $form->field($model, 'subdomain')
            ?>

            <?=
            $form->field(
                $model,
                'h1',
                [
                    'copyFrom'=>[
                        "#page-title",
                        "#page-breadcrumbs_label",
                    ]
                ]
            )
            ?>

            <?=
            $form->field(
                $model,
                'breadcrumbs_label',
                [
                    'copyFrom'=>[
                        "#page-title",
                        "#page-h1",
                    ]
                ]
            )
            ?>

            <?=$form->field($model, 'meta_description')->textarea()?>

            <?php BackendWidget::end(); ?>

            <?=
            \app\properties\PropertiesWidget::widget(
                [
                    'model' => $model,
                    'form' => $form,
                ]
            );
            ?>

        </article>

    </div>
</section>
<?php
$event = new \app\backend\events\BackendEntityEditFormEvent($form, $model);
$this->trigger(PageController::BACKEND_PAGE_EDIT_FORM, $event);
?>
<?php ActiveForm::end(); ?>
