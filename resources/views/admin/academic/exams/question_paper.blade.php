@extends('admin.layouts.app')
@section('title', 'Exams')
@section('title2', 'Question Paper')

@section('content')

@if(session('success') || session('status') || session('error'))
@include('admin.layouts.common.status')
@endif

<section class="section">

    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <div class="card-header-left">
                        <h5>
                            <i class="bi bi-plus-square"></i>
                            <span class="ms-1">@yield('title2')</span>
                        </h5>
                        <nav style="--bs-breadcrumb-divider: '•';">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bi bi-house"></i></a></li>
                                <li class="breadcrumb-item "><a href="{{ route('admin.academic.exams.index') }}">@yield('title')</a></li>
                                <li class="breadcrumb-item active">@yield('title2')</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="card-header-right">
                        <a href="{{ route('admin.academic.exams.index') }}" class="btn btn-outline-theme btn-sm">
                            <i class="bi bi-arrow-left-square"></i>
                            <span class="ms-1">Back to List</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-3">
            
            <div class="card" id="customize_card">

                <div class="card-header">
                    <h6 class="text-dark mb-0"><strong>Customize Q. Paper</strong></h6>
                </div>

                <div class="card-body">
                    <div class="exam-infos mb-3">
                        <div class="exam-instructions d-flex align-items-center justify-content-between">                            
                            <label for="exam_instructions" class="form-label"><small>Instructions</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_instructions" checked="">
                            </div>
                        </div>
                        
                        <div class="exam-basic-rules d-flex align-items-center justify-content-between">                            
                            <label for="exam_basic_rules" class="form-label"><small>Basic Rules</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_basic_rules" checked="">
                            </div>
                        </div>
                        
                        <div class="exam-marks d-flex align-items-center justify-content-between">                            
                            <label for="exam_marks" class="form-label"><small>Marks</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_marks" checked="">
                            </div>
                        </div>
                        
                        <div class="exam-difficulty-level d-flex align-items-center justify-content-between">                            
                            <label for="exam_difficulty_level" class="form-label"><small>Difficulty Level</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_difficulty_level" checked="">
                            </div>
                        </div>
                        
                        <div class="exam-question-type d-flex align-items-center justify-content-between">                            
                            <label for="exam_question_type" class="form-label"><small>Question Type</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_question_type" checked="">
                            </div>
                        </div>
                        
                        <div class="exam-evaluation-type d-flex align-items-center justify-content-between">                            
                            <label for="exam_evaluation_type" class="form-label"><small>Evaluation Type</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_evaluation_type" checked="">
                            </div>
                        </div>
                        
                        <div class="exam-correct-answer d-flex align-items-center justify-content-between">                            
                            <label for="exam_correct_answer" class="form-label"><small>Answer</small></label>
                            <div class="form-check form-switch d-flex justify-content-end">
                                <input class="form-check-input" type="checkbox" id="exam_correct_answer" checked="">
                            </div>
                        </div>
                    </div> 
                    
                    <div class="print-download-btns text-center mb-2">
                        <button id="printBtn" class="btn btn-outline-secondary btn-sm w-100 mb-2">
                            <i class="bi bi-printer"></i>
                            <span class="ms-1">Print</span>
                        </button>
                        <button id="downloadBtn" class="btn btn-outline-theme btn-sm w-100">
                            <i class="bi bi-download"></i>
                            <span class="ms-1">Download</span>
                        </button>
                    </div>                   
                </div>

            </div>

        </div>

        <div class="col-lg-9">

            <div class="card" id="question_paper_card">
                
                <div class="card-header">
                    <h5 class="text-dark text-center mb-3"><strong>{{ $exam->exam_title }} [{{ $exam->exam_code }}]</strong></h5>

                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="course-info text-dark text-start">
                            <h6 class="mb-0"><strong>Course Title:</strong> {{ $exam->course->course_title }}</h6>
                            <h6 class="mb-0"><strong>Course Code:</strong> {{ $exam->course->course_code }}</h6>
                        </div>
                        <div class="exam-info text-dark text-end">
                            <h6 class="mb-0"><strong>Duration:</strong> {{ $exam->exam_duration_min ?? '0' }} mins</h6>
                            <h6 class="mb-0"><strong>Total Marks:</strong> {{ intval($exam->total_marks) ?? '0' }}</h6>
                        </div>
                    </div>

                    @if ($exam->instructions == NULL)
                    <div class="exam_paper_instructions d-none"></div>
                    @else
                    <div class="exam_paper_instructions text-dark text-center small w-50 m-auto mt-1">
                        <p class="mb-0">{{ $exam->instructions }}</p>
                    </div>
                    @endif

                    @if ($exam->basic_rules == NULL)
                    <div class="exam_paper_basic_rules d-none"></div>
                    @else
                    <div class="exam_paper_basic_rules text-dark text-center small w-50 m-auto mt-1">
                        <p class="mb-0">{{ $exam->basic_rules }}</p>
                    </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class="question-list">
                        @if ($exam->questions->count() > 0)
                        @foreach ($exam->questions as $question)
                        <div class="question-item mb-3 p-3 border rounded">
                            <div class="d-flex align-items-baseline justify-content-between mb-2">
                                <p class="question mb-0" style="width: 95%;"><strong>Q{{ $question->question_order }}:</strong> {{ $question->question_text }}</p>

                                <p class="exam_paper_marks fw-bold text-end mb-0" style="width: 5%;">{{ intval($question->marks) }}</p>
                            </div>

                            <div class="question-figure">
                                @if($question->question_figure)
                                <div class="p-2 border rounded w-50 m-auto mb-2">
                                    <img src="{{ asset('storage/question_figure/' . $question->question_figure) }}" alt="Question Figure" class="img-fluid" style="width:100%; max-height: 200px">
                                </div>
                                @endif
                            </div>

                            <div class="question-info d-flex align-items-center gap-1 mb-2">
                                <p class="exam_paper_difficulty_level mb-0 fw-bold">
                                    @if($question->difficulty_level == 'easy')
                                    <span class="badge rounded-pill bg-success">Easy</span>
                                    @elseif($question->difficulty_level == 'medium')
                                    <span class="badge rounded-pill bg-warning">Medium</span>
                                    @else
                                    <span class="badge rounded-pill bg-danger">Hard</span>
                                    @endif
                                </p>

                                <p class="exam_paper_question_type mb-0">
                                    @if($question->question_type == 'mcq_2')
                                    <span class="badge rounded-pill bg-info text-dark">MCQ (2 options)</span>
                                    @elseif($question->question_type == 'mcq_4')
                                    <span class="badge rounded-pill bg-info text-dark">MCQ (4 options)</span>
                                    @elseif($question->question_type == 'short_question')
                                    <span class="badge rounded-pill bg-info text-dark">Short Question</span>
                                    @else
                                    <span class="badge rounded-pill bg-info text-dark">Long Question</span>
                                    @endif
                                </p>

                                <p class="exam_paper_evaluation_type mb-0">
                                    @if($question->evaluation_type == 'automatic')
                                    <span class="badge rounded-pill bg-dark">Automatic</span>
                                    @else
                                    <span class="badge rounded-pill bg-dark">Manual</span>
                                    @endif
                                </p>
                            </div>

                            <div class="options d-flex align-items-center gap-5 mb-2">
                                @if ($question->option_a) <p class="mb-0"><strong>A.</strong> {{ $question->option_a }}</p> @endif
                                @if ($question->option_b) <p class="mb-0"><strong>B.</strong> {{ $question->option_b }}</p> @endif
                                @if ($question->option_c) <p class="mb-0"><strong>C.</strong> {{ $question->option_c }}</p> @endif
                                @if ($question->option_d) <p class="mb-0"><strong>D.</strong> {{ $question->option_d }}</p> @endif
                            </div>
                            <p class="exam_paper_correct_answer text-success mb-0"><strong>Answer:</strong> {{ $question->correct_answer }}</p>
                        </div>
                        @endforeach
                        @else
                        <p class="text-center text-muted">Right now no questions are available for this exam.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</section>

@endsection




@section('scripts')

{{-- Status: Active / Deactive --}}
<script>
    function updateLabelText(checkbox) {
        const label = document.getElementById("isActiveLabel");
        const span = label.querySelector("span"); // Get the <span> with the badge
        const icon = span.querySelector("i"); // Get the icon element

        if (checkbox.checked) {
            span.classList.remove("bg-danger"); // Remove danger class (Deactive)
            span.classList.add("bg-success"); // Add success class (Active)
            icon.classList.remove("bi-x-square"); // Remove the 'x' icon (Deactive)
            icon.classList.add("bi-check-square"); // Add the 'check' icon (Active)
            span.innerHTML = '<i class="bi bi-check-square me-1"></i> Active'; // Update the text content to Active
        } else {
            span.classList.remove("bg-success"); // Remove success class (Active)
            span.classList.add("bg-danger"); // Add danger class (Deactive)
            icon.classList.remove("bi-check-square"); // Remove the 'check' icon (Active)
            icon.classList.add("bi-x-square"); // Add the 'x' icon (Deactive)
            span.innerHTML = '<i class="bi bi-x-square me-1"></i> Deactive'; // Update the text content to Deactive
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_active');
        if (checkbox) {
            updateLabelText(checkbox);
        }
    });

</script>

<script>
    $(document).ready(function() {
        // $("#course_id").select2({});
    });

</script>


<script>
    function calculateDuration() {
        const start = document.getElementById('start_time').value;
        const end = document.getElementById('end_time').value;

        if (start && end) {
            const [startH, startM] = start.split(':').map(Number);
            const [endH, endM] = end.split(':').map(Number);

            const startTotal = startH * 60 + startM;
            const endTotal = endH * 60 + endM;
            const diff = endTotal - startTotal;

            document.getElementById('exam_duration_min').value = diff > 0 ? diff : 0;
        }
    }

    document.getElementById('start_time').addEventListener('change', calculateDuration);
    document.getElementById('end_time').addEventListener('change', calculateDuration);
</script>

<script>
    function smoothToggle(className, show) {
        const elements = document.querySelectorAll('.' + className);

        elements.forEach(el => {
            if (show) {
                el.style.display = '';
                requestAnimationFrame(() => {
                    el.style.maxHeight = '200px';
                    el.style.opacity   = '1';
                });
            } else {
                el.style.maxHeight = '0';
                el.style.opacity   = '0';
                el.addEventListener('transitionend', function handler() {
                    el.style.display = 'none';
                    el.removeEventListener('transitionend', handler);
                });
            }
        });
    }

    // Instructions toggle (still uses id since it's outside the loop)
    document.getElementById('exam_instructions').addEventListener('change', function () {
        smoothToggle('exam_paper_instructions', this.checked);
    });

    document.getElementById('exam_basic_rules').addEventListener('change', function () {
        smoothToggle('exam_paper_basic_rules', this.checked);
    });

    // These now target all questions at once via class
    document.getElementById('exam_marks').addEventListener('change', function () {
        smoothToggle('exam_paper_marks', this.checked);
    });

    document.getElementById('exam_difficulty_level').addEventListener('change', function () {
        smoothToggle('exam_paper_difficulty_level', this.checked);
    });

    document.getElementById('exam_question_type').addEventListener('change', function () {
        smoothToggle('exam_paper_question_type', this.checked);
    });

    document.getElementById('exam_evaluation_type').addEventListener('change', function () {
        smoothToggle('exam_paper_evaluation_type', this.checked);
    });

    document.getElementById('exam_correct_answer').addEventListener('change', function () {
        smoothToggle('exam_paper_correct_answer', this.checked);
    });
    
    // Print 
    document.getElementById('printBtn').addEventListener('click', function () {
        window.print();
    });
</script>




<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
    document.getElementById('downloadBtn').addEventListener('click', async function () {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Generating...';

        const { jsPDF } = window.jspdf;
        const card = document.getElementById('question_paper_card');

        const canvas = await html2canvas(card, {
            scale: 2,
            useCORS: true,
            scrollY: -window.scrollY
        });

        const imgData    = canvas.toDataURL('image/png');
        const pdf        = new jsPDF('p', 'mm', 'a4');
        const pageWidth  = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        const imgWidth   = pageWidth;
        const imgHeight  = (canvas.height * imgWidth) / canvas.width;

        let yPosition  = 0;
        let heightLeft = imgHeight;

        pdf.addImage(imgData, 'PNG', 0, yPosition, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft > 0) {
            yPosition -= pageHeight;
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 0, yPosition, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        pdf.save('{{ Str::slug($exam->exam_title) }}-{{ $exam->exam_code }}.pdf');

        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-download"></i> <span class="ms-1">Download</span>';
    });
</script>

@endsection

