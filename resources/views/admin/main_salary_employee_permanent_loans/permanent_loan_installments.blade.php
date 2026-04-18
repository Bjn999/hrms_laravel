      <!-- Section فوق الجدول -->
      <div class="mb-4 p-3 border rounded bg-light">
          {{-- <h6 class="mb-3">بيانات القسط</h6> --}}
          <div class="row">
              <div class="col-md-6">
                  <label class="fw-bold">الإضافه:</label>
                  <div>
                      @php
                      $create_date = new DateTime($p_loan_data->created_at);
                      $update_date = new DateTime($p_loan_data->updated_at);
                      @endphp
                      ({{ $create_date->format('Y-m-d') }})
                      ({{ $create_date->format('h-i') }}
                      {{ $create_date->format('A') }})
                      بواسطة
                      <strong> {{ $p_loan_data->added->name }} </strong>
                  </div>
              </div>
              <div class="col-md-6">
                  <label class="fw-bold">التحديث:</label>
                  <div>
                      @if ($p_loan_data->updated_by > 0)
                      ({{ $update_date->format('Y-m-d') }})
                      ({{ $update_date->format('h-i') }}
                      {{ $update_date->format('A') }})
                      بواسطة
                      <strong> {{ $p_loan_data->updatedby->name }} </strong>
                      @else
                      لم يتم التحديث بعد
                      @endif
                  </div>
              </div>
          </div>
      </div>

      @if (@isset($p_loan_installments_data) and !@empty($p_loan_installments_data) and count($p_loan_installments_data) > 0)
      <div style="max-height: 40vh; overflow-y: auto">
          <table id="example2" class="table table-bordered table-hover text-center">
              <thead class="custom_thead">
                  <th style="vertical-align: middle"> قيمة القسط </th>
                  <th style="vertical-align: middle"> شهر الاستحقاق </th>
                  <th style="vertical-align: middle"> الحالة </th>
                  <th style="vertical-align: middle"> الأرشفة </th>
              </thead>
              <tbody>
                  @foreach ($p_loan_installments_data as $info)
                  <tr style="cursor: pointer">
                      <td style="vertical-align: middle"> {{ $info->monthly_installment_value * 1 }} رس </td>
                      <td style="vertical-align: middle"> {{ $info->year_and_month }} </td>
                      <td style="vertical-align: middle"> @if ($info->status == 0) لم يتم الدفع @elseif ($info->status == 1) تم الدفع على الراتب @elseif ($info->status == 2) تم الدفع كاش @endif </td>
                      <td style="vertical-align: middle"> @if ($info->is_archived == 0) غير مرشف @else مؤرشف @endif </td>
                  </tr>
                  @endforeach
              </tbody>
          </table>
      </div>

      @else
      <p class="text-danger text-center font-weight-bold my-5 mx-auto">لا توجد بيانات لعرضها</p>
      @endif
