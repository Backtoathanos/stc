@extends('layouts.header')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Payroll Parameter</h3>
    @if(isset($company))
    <div class="card-tools">
      <span class="badge badge-primary">
        <i class="fas fa-building mr-1"></i> {{ $company->name }}
      </span>
    </div>
    @endif
  </div>
  <div class="card-body">
    <form id="payrollParameterForm">
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-6">
          <div class="form-group">
            <label for="pf_percentage">PF % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="pf_percentage" name="pf_percentage" value="{{ $parameter->pf_percentage ?? 12 }}" required>
          </div>
          
          <div class="form-group">
            <label for="ppf_percentage">PPF % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="ppf_percentage" name="ppf_percentage" value="{{ $parameter->ppf_percentage ?? 3.67 }}" required>
          </div>
          
          <div class="form-group">
            <label for="ac_no_2_pf_percentage">A/C NO 2 P.F % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="ac_no_2_pf_percentage" name="ac_no_2_pf_percentage" value="{{ $parameter->ac_no_2_pf_percentage ?? 0.85 }}" required>
          </div>
          
          <div class="form-group">
            <label for="ac_22_pf_percentage">A/C 22 PF % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="ac_22_pf_percentage" name="ac_22_pf_percentage" value="{{ $parameter->ac_22_pf_percentage ?? 0.01 }}" required>
          </div>
          
          <div class="form-group">
            <label for="esic_employee_percentage">ESIC EMPLOYEE % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="esic_employee_percentage" name="esic_employee_percentage" value="{{ $parameter->esic_employee_percentage ?? 3.25 }}" required>
          </div>
          
          <div class="form-group">
            <label for="previous_month">PREVIOUS MONTH</label>
            <div class="input-group date" id="previous_month" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#previous_month" name="previous_month" value="{{ $parameter->previous_month ? \Carbon\Carbon::parse($parameter->previous_month)->format('F, Y') : '' }}" placeholder="Select Month">
              <div class="input-group-append" data-target="#previous_month" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="sunday">SUNDAY <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="sunday" name="sunday" value="{{ $parameter->sunday ?? 5 }}" required>
          </div>
          
          <div class="form-group">
            <label for="manday">MANDAY <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="manday" name="manday" value="{{ $parameter->manday ?? 25 }}" required>
          </div>
          
          <div class="form-group">
            <label for="esic_limit">ESIC LIMIT <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="esic_limit" name="esic_limit" value="{{ $parameter->esic_limit ?? 21000 }}" required>
          </div>
          
          <div class="form-group">
            <label for="bonus_start_date">BONUS START DATE</label>
            <div class="input-group date" id="bonus_start_date" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#bonus_start_date" name="bonus_start_date" value="{{ $parameter->bonus_start_date ? \Carbon\Carbon::parse($parameter->bonus_start_date)->format('d-m-Y') : '' }}" placeholder="DD-MM-YYYY">
              <div class="input-group-append" data-target="#bonus_start_date" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Right Column -->
        <div class="col-md-6">
          <div class="form-group">
            <label for="epf_percentage">EPF % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="epf_percentage" name="epf_percentage" value="{{ $parameter->epf_percentage ?? 8.33 }}" required>
          </div>
          
          <div class="form-group">
            <label for="if_percentage">IF % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="if_percentage" name="if_percentage" value="{{ $parameter->if_percentage ?? 0.50 }}" required>
          </div>
          
          <div class="form-group">
            <label for="ac_21_pf_percentage">A/C 21 PF % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="ac_21_pf_percentage" name="ac_21_pf_percentage" value="{{ $parameter->ac_21_pf_percentage ?? 0.50 }}" required>
          </div>
          
          <div class="form-group">
            <label for="esic_employer_percentage">ESIC EMPLOYER % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="esic_employer_percentage" name="esic_employer_percentage" value="{{ $parameter->esic_employer_percentage ?? 0.75 }}" required>
          </div>
          
          <div class="form-group">
            <label for="current_month">CURRENT MONTH</label>
            <div class="input-group date" id="current_month" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#current_month" name="current_month" value="{{ $parameter->current_month ? \Carbon\Carbon::parse($parameter->current_month)->format('F, Y') : '' }}" placeholder="Select Month">
              <div class="input-group-append" data-target="#current_month" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="total_days">TOTAL DAYS <span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="total_days" name="total_days" value="{{ $parameter->total_days ?? 30 }}" required>
          </div>
          
          <div class="form-group">
            <label for="holiday_percentage">HOLIDAY % <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="holiday_percentage" name="holiday_percentage" value="{{ $parameter->holiday_percentage ?? 1.00 }}" required>
          </div>
          
          <div class="form-group">
            <label for="pf_limit">PF LIMIT <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control" id="pf_limit" name="pf_limit" value="{{ $parameter->pf_limit ?? 15000 }}" required>
          </div>
          
          <div class="form-group">
            <label for="leave_start_date">LEAVE START DATE</label>
            <div class="input-group date" id="leave_start_date" data-target-input="nearest">
              <input type="text" class="form-control datetimepicker-input" data-target="#leave_start_date" name="leave_start_date" value="{{ $parameter->leave_start_date ? \Carbon\Carbon::parse($parameter->leave_start_date)->format('d-m-Y') : '' }}" placeholder="DD-MM-YYYY">
              <div class="input-group-append" data-target="#leave_start_date" data-toggle="datetimepicker">
                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row mt-3">
        <div class="col-md-12 text-right">
          <button type="submit" class="btn btn-dark btn-lg">
            <i class="fas fa-save"></i> Save
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize date pickers for month selection (Previous Month and Current Month)
    $('#previous_month').datetimepicker({
        format: 'MMMM, YYYY',
        viewMode: 'months',
        minViewMode: 'months',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check-o',
            clear: 'far fa-trash',
            close: 'far fa-times'
        }
    });
    
    $('#current_month').datetimepicker({
        format: 'MMMM, YYYY',
        viewMode: 'months',
        minViewMode: 'months',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check-o',
            clear: 'far fa-trash',
            close: 'far fa-times'
        }
    });
    
    // Initialize date pickers for date selection (Bonus Start Date and Leave Start Date)
    $('#bonus_start_date').datetimepicker({
        format: 'DD-MM-YYYY',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check-o',
            clear: 'far fa-trash',
            close: 'far fa-times'
        }
    });
    
    $('#leave_start_date').datetimepicker({
        format: 'DD-MM-YYYY',
        icons: {
            time: 'far fa-clock',
            date: 'far fa-calendar',
            up: 'fas fa-arrow-up',
            down: 'fas fa-arrow-down',
            previous: 'fas fa-chevron-left',
            next: 'fas fa-chevron-right',
            today: 'far fa-calendar-check-o',
            clear: 'far fa-trash',
            close: 'far fa-times'
        }
    });
    
    // Form submission
    $('#payrollParameterForm').on('submit', function(e) {
        e.preventDefault();
        
        // Convert month fields to proper date format
        var formData = $(this).serializeArray();
        var data = {};
        
        formData.forEach(function(item) {
            if (item.name === 'previous_month' || item.name === 'current_month') {
                // Convert "October, 2025" to "2025-10-01"
                if (item.value && item.value.trim() !== '') {
                    try {
                        var date = moment(item.value, 'MMMM, YYYY');
                        if (date.isValid()) {
                            data[item.name] = date.format('YYYY-MM-01');
                        } else {
                            data[item.name] = null;
                        }
                    } catch (e) {
                        data[item.name] = null;
                    }
                } else {
                    data[item.name] = null;
                }
            } else if (item.name === 'bonus_start_date' || item.name === 'leave_start_date') {
                // Convert "01-04-2024" to "2024-04-01"
                if (item.value && item.value.trim() !== '') {
                    try {
                        var date = moment(item.value, 'DD-MM-YYYY');
                        if (date.isValid()) {
                            data[item.name] = date.format('YYYY-MM-DD');
                        } else {
                            data[item.name] = null;
                        }
                    } catch (e) {
                        data[item.name] = null;
                    }
                } else {
                    data[item.name] = null;
                }
            } else {
                data[item.name] = item.value;
            }
        });
        
        $.ajax({
            url: window.appBaseUrl + '/settings/payroll-parameter',
            type: 'POST',
            data: data,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message || 'Payroll parameters saved successfully',
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Failed to save payroll parameters'
                    });
                }
            },
            error: function(xhr) {
                var message = 'Failed to save payroll parameters';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    message = Object.values(errors).flat().join('<br>');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    html: message
                });
            }
        });
    });
});
</script>
@endpush

