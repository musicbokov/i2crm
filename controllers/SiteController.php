<?php

namespace app\controllers;

use app\components\entity\Exams;
use app\components\entity\Schedule;
use app\components\entity\ScheduleTypes;
use app\components\exceptions\ScheduleExceptions;
use app\components\operations\SchedulerOperation;
use app\models\ExamScheduleModel;
use Exception;
use Throwable;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Страница добавления экзамена в расписание
     * @return string
     * @throws Throwable
     */
    public function actionIndex()
    {
        $title = 'Главная страница';
        $query = Exams::find()->with(['schedule']);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);
        return $this->render('index', [
            'title' => $title,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Формирование страницы "Планировщик"
     * @throws Throwable
     */
    public function actionScheduler()
    {
        $examScheduleModel = new ExamScheduleModel();
        $title = 'Расписание для экзаменов';
        $errorMessage = '';

        if ($examScheduleModel->load(Yii::$app->request->post()) && $examScheduleModel->validate()) {
            try {
                $examScheduleModel->saveExamsWithSchedule();
                Yii::$app->session->setFlash('success', 'Запись сохранена');
                return $this->refresh();
            } catch (ScheduleExceptions $ex) {
                $errorMessage = $ex->getMessage();
            } catch (Exception $ex) {
                $errorMessage = 'fatal error';
            }
        }

        return $this->render('scheduler', [
            'title' => $title,
            'examScheduleModel' => $examScheduleModel,
            'errorMessage' => $errorMessage,
        ]);
    }

    /**
     * Формирование страницы "Календарь"
     * @return string
     */
    public function actionSchedule()
    {
        $title = 'Расписание';

        $examsScheduleEntity = Schedule::find()->all();
        $examsSchedule = ArrayHelper::map($examsScheduleEntity, function (Schedule $schedule) {
            return date('Y-m-d', strtotime($schedule->date));
        }, function (Schedule $schedule) {
            return ['examName' => $schedule->exam->name, 'educationDaysCount' => $schedule->exam->days];
        });
        return $this->render('schedule', [
            'title' => $title,
            'examsSchedule' => $examsSchedule,
        ]);
    }

    /**
     * Возвращает расписание в соответствии с его типом
     * @return string
     */
    public function actionGetScheduleExamsByType()
    {
        if ($post = Yii::$app->request->post()) {
            $types = [];
            if (json_decode($post['exams'])) {
                $types[] = ScheduleTypes::TYPE_EXAM;
            }
            if (json_decode($post['preparing'])) {
                $types[] = ScheduleTypes::TYPE_PREPARING;
            }
            $schedules = Schedule::find()->with(['exam', 'type'])->where(['type_id' => $types])->all();
            $schedule = ArrayHelper::map($schedules, function (Schedule $schedule) {
                return date('Y-m-d', strtotime($schedule->date));
            }, function (Schedule $schedule) {
                return [
                    'examName' => $schedule->exam->name,
                    'scheduleType' => $schedule->type->name
                ];
            });

            return json_encode($schedule);
        }
    }

    /**
     * Удаление экзамена из базы
     * @param int $id Идентификатор экзамена
     * @return Response
     */
    public function actionDelete($id)
    {
        Exams::deleteAll(['id' => $id]);
        return $this->redirect('index');
    }

    /**
     * Формирует и отображает расписание
     * @return string
     * @throws Exception
     */
    public function actionCreateSchedule()
    {
        $scheduler = new SchedulerOperation();
        $scheduler->createSchedule();
        $schedulePreparing = $scheduler->getSchedulePreparing();
        $preparingModels = [];
        foreach ($schedulePreparing as $exam => $schedule) {
            $preparingModels[] = [
                'name' => $exam,
                'datePreparing' => date('d.m.Y', strtotime(key($schedule))),
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $preparingModels,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->renderAjax('scheduler/result', [
            'dataProvider' => $dataProvider
        ]);
    }
}
