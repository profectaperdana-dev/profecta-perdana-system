<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\AnswerModel;
use App\Models\ItemPromotionCategoryModel;
use App\Models\ItemPromotionModel;
use App\Models\JobDescription;
use App\Models\JobQualification;
use App\Models\JobVacancies;
use App\Models\QuestionModel;
use App\Models\WarehouseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JobVacanciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        if ($request->ajax()) {
            $data = JobVacancies::get();
            return datatables()->of($data)
                ->editColumn('question', function ($data) {
                    $output = [];
                    $alphabet = 'a'; // Inisialisasi abjad awal
                    foreach ($data->jobQuestion as $key => $value) {
                        $output[] = '<span class="">' . ($key + 1) . '. ' . $value->question;
                        foreach ($value->questAnswer as $key2 => $value2) {
                            $output[] = '<span class="">&emsp;&emsp;' . $alphabet . '. ' . $value2->answer . '</span>';
                            $alphabet++; // Ganti ke abjad berikutnya
                        }
                        $alphabet = 'a'; // Reset abjad ke awal
                    }

                    return implode('<br>', $output);
                })

                ->editColumn('job_description', function ($data) {
                    $desc = [];

                    foreach ($data->jobDescription as $key => $value) {
                        $desc[] = '<span class="">' . ($key + 1) . '. ' . $value->name . '</span>';
                    }

                    return implode('<br>', $desc);
                })
                ->editColumn('job_qualification', function ($data) {
                    $desc = [];

                    foreach ($data->jobQualification as $key => $value) {
                        $desc[] = '<span class="">' . ($key + 1) . '. ' . $value->name . '</span>';
                    }

                    return implode('<br>', $desc);
                })
                ->editColumn('status', function ($data) {
                    return $data->status == '1' ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
                })
                ->editColumn('date_post', function ($data) {
                    return date('d F Y', strtotime($data->date_post));
                })
                ->editColumn('end_date', function ($data) {
                    return date('d F Y', strtotime($data->end_date));
                })
                ->addColumn('position', function ($data) {
                    return view('job_vacancies.option', ['data' => $data])->render();
                })
                ->rawColumns(['job_description', 'position', 'job_qualification', 'status', 'question'])
                ->addIndexColumn()
                ->make(true);
        }
        $datas = [
            'title' => 'Create Job Vacancies',

        ];

        return view('job_vacancies.index', $datas);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = new JobVacancies();
            $data->title = $request->title;
            $data->date_post = $request->date_post;
            $data->end_date = $request->end_date;
            $data->description = $request->description;
            $data->position = $request->position;
            $data->unique_id = Str::random(15);
            $data->status = 1;
            if ($data->save()) {
                foreach ($request->job_description as $key => $value) {
                    $description = new JobDescription();
                    $description->job_vacancies_id = $data->id;
                    $description->name = $value['name'];
                    $description->save();
                }
                foreach ($request->job_qualification as $key => $value) {
                    $description = new JobQualification();
                    $description->job_vacancies_id = $data->id;
                    $description->name = $value['name'];
                    $description->save();
                }
                $quest_id = '';
                foreach ($request->job_question as $key => $quest) {
                    // dd($quest['question']);
                    $data_quest = new QuestionModel();
                    $data_quest->job_vacancies_id = $data->id;
                    $data_quest->question = $quest['question'];
                    $data_quest->save();
                    $quest_id = $data_quest->id;

                    for ($i = 0; $i < count($quest) - 1; $i++) {
                        $data_answer = new AnswerModel();
                        $data_answer->question_id = $quest_id;
                        $data_answer->answer = $quest[$i]['answer'];
                        $data_answer->save();
                    }
                }
            }
            DB::commit();
            // dd($data);
            return redirect()->back()->with('success', 'Job Vacancies has been created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = JobVacancies::find($id);
            $data->title = $request->title;
            $data->date_post = $request->date_post;
            $data->end_date = $request->end_date;
            $data->description = $request->description;
            $data->position = $request->position;
            $data->status = $request->status;
            if ($data->save()) {
                $data->jobDescription()->delete();
                foreach ($request->job_description as $key => $value) {
                    $description = new JobDescription();
                    $description->job_vacancies_id = $data->id;
                    $description->name = $value['name'];
                    $description->save();
                }
                $data->jobQualification()->delete();
                foreach ($request->job_qualification as $key => $value) {
                    $description = new JobQualification();
                    $description->job_vacancies_id = $data->id;
                    $description->name = $value['name'];
                    $description->save();
                }
                // $data->jobQuestion()->delete();
                $quest_id = '';
                foreach ($request->job_question as $key => $quest) {


                    $question = QuestionModel::where('id', $quest['quest_id'])->where('job_vacancies_id', $data->id)->first();
                    if ($question) {
                        $question->question = $quest['question'];
                        $question->save();
                    } else {
                        $question = new QuestionModel();
                        $question->job_vacancies_id = $data->id;
                        $question->question = $quest['question'];
                        $question->save();
                    }
                    $quest_id = $question->id;

                    // dd($quest['question']);


                    // AnswerModel::where('question_id', $quest_id)->delete();
                    for ($i = 0; $i < count($quest) - 2; $i++) {

                        if (isset($quest[$i]['answer_id'])) {
                            $data_answer =  AnswerModel::where('id', $quest[$i]['answer_id'])->where('question_id', $quest_id)->first();
                        } else {
                            $data_answer = null;
                        }

                        if ($data_answer) {
                            $data_answer->answer = $quest[$i]['answer'];
                            $data_answer->save();
                        } else {
                            $data_answer = new AnswerModel();
                            $data_answer->question_id = $quest_id;
                            $data_answer->answer = $quest[$i]['answer'];
                            $data_answer->save();
                        }
                    }
                }
            }
            DB::commit();
            return redirect()->back()->with('success', 'Job Vacancies has been updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $data = JobVacancies::find($id);
            $data->delete();
            $data->jobDescription()->delete();
            $data->jobQualification()->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Job Vacancies has been deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
