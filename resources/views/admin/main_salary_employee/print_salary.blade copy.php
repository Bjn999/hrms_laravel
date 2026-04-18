<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title> طباعة راتب </title>
      <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
      <link rel="stylesheet" href="{{ url('assets/admin/css/bootstrap_rtl-v4.2.1/bootstrap.min.css')}}">
   </head>
   <style>
      .custom_td_fisrt{width: 30%;background-color: lightcyan;}
      td,th{text-align:  center;color: black;}
      .underPrag{text-decoration: underline; font-size: 16px; color: black; font-weight: bold; margin: 7px;}
      @media print{
            #printButton{
               display: none;
            }
         }
  </style>
   <body >
      <div class="container">
        <p style="text-align: center;  padding: 3px;">
      مفردات المرتب 
         <a  href="#" id="printButton" class=" btn btn-success btn-xs  hidden-print" onclick="window.print();">
         طباعة             
         </a> 
        </p>
         @if (@isset($data) && !@empty($data) )

         <style>
            .custom_td_fisrt{width: 30%;background-color: lightcyan;}
            td,th{text-align:  center;color: black;}
            .underPrag{text-decoration: underline; font-size: 16px; color: black; font-weight: bold; margin: 7px;}
         </style>
         <table   dir="rtl"    cellspacing="1" cellpadding="3" border="2"    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto; background-color: lightgray ">
          <tr>
            <td style="width: 20%"> الشهر المالي</td>
        
            <td>
               (   {{ $other['Finance_cln_period_Data']['name'] }}  ) لسنة {{ $other['Finance_cln_period_Data']['FINANCE_YR'] }}

            </td>
         </tr>
          <tr>
               <td style="width: 20%">الرقم الوظيفي</td>
            
               <td>
                  (  كود رقم   {{  $other['Employee_data']['employee_code'] }} ) 
               </td>
            </tr>
            <tr>
               <td style="width: 20%"> الوظيفة</td>
               <td >  {{  $other['Employee_data']['Job_name'] }}</td>
            </tr>
         </table>
         <p class="underPrag" >أولاً : الاستحقاقات</p>
         <table   dir="rtl"    cellspacing="1" cellpadding="3" border="2"    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
            <tr>
               <td rowspan="8"  style="width: 10%;-webkit-transform: rotate(-90deg) !important;"> الاستحقاقات</td>
          
            </tr>
            @if($data['last_salary_remain_blance']<0)
            <tr>
          
               <td> رصيد مرحل من الشهر السابق</td>
               <td style="text-align: right;">
                  {{ $other['last_salary_remain_blance']*1*(-1) }} 
               </td>
            </tr>

            @endif
            <tr>
          
              <td style="width: 20%;"> الراتب</td>
              <td style="text-align: right;">

( @if($data['money_for_day']<0) {{ $data['money_for_day']*1*(-1) }} @else {{ $data['money_for_day']*1 }} @endif) طبقا لعدد ايام الحضور وهي {{ $data['number_of_days_attendance']*1 }}
       وملاحظة الاساسي هو (@if($data['Basic_salary']<0) {{ $data['Basic_salary']*1*(-1) }} @else {{ $data['Basic_salary']*1 }} @endif   )

              </td>
           </tr>
            <tr>
          
               <td> الاضافي</td>
               <td style="text-align: right;">
                  {{ $other['total_addtion']*1 }} 
               </td>
            </tr>
            <tr>
               <td> الحافز قيمة</td>
               <td style="text-align: right;">
                  @if($data['Motivation_value']<0) {{ $data['Motivation_value']*1*(-1) }} @else {{ $data['Motivation_value']*1 }} @endif   

               </td>
            </tr>
            <tr>
               <td>بدلات ثابتة</td>
               <td style="text-align: right;">
                  @if($data['allowances_value']<0) {{ $data['allowances_value']*1*(-1) }} @else {{ $data['allowances_value']*1 }} @endif   

               </td>
            </tr>
            <tr>
              <td> خصم تم تحصيله</td>
              <td style="text-align: right;">
               {{ $other['total_discount_collected_instant']*1 }}  

              </td>
           </tr>
           <tr>
            <td>  رد سلفة تم تحصيله</td>
            <td style="text-align: right;">
               @if($other['total_loan_return']<0) {{ $other['total_loan_return']*1*(-1) }} @else {{ $other['total_loan_return']*1 }} @endif   


            </td>
         </tr>
           
            <tr  style=" background-color: lightblue  !important;;">
               <td >اجمالي الاستحقاقات</td>
               <td style="text-align: right;">
                  {{ $data['tota_credit']*1*(-1) }}
               </td>
            </tr>
         </table>
         <p class="underPrag" >ثانياً : الاستقطاعات</p>
         <table   dir="rtl"    cellspacing="1" cellpadding="3" border="2"    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
          <tr>
             <td rowspan="8"  style="width: 10%;-webkit-transform: rotate(-90deg) !important;"> الاستحقاقات</td>
        
          </tr>
          @if($data['last_salary_remain_blance']>0)
          <tr>
        
             <td style="width: 20%;"> رصيد مرحل من الشهر السابق</td>
             <td style="text-align: right;">
                {{ $data['last_salary_remain_blance']*1 }} 
             </td>
          </tr>

          @endif
          <tr>
        
             <td> الخصومات</td>
             <td style="text-align: right;">
               {{  $other['total_discount']*1 }}
            </td>
          </tr>
          <tr>
             <td>  خصم التأمين الاجتماعي</td>
             <td style="text-align: right;">
               @if($data['social_insurance_value']<0) {{ $data['social_insurance_value']*1*(-1) }} @else {{ $data['social_insurance_value']*1 }} @endif   

            </td>
          </tr>
          <tr>
             <td> سلف مصروفة</td>
             <td style="text-align: right;">
               @if($other['total_loan']<0) {{ $other['total_loan']*1*(-1) }} @else {{ $other['total_loan']*1 }} @endif   

            </td>
          </tr>
          <tr>
            <td> اضافي تم صرفه لحظياً</td>
            <td style="text-align: right;">
               {{ $other['total_addtion_dissmissial_instant']*1 }}  

            </td>
         </tr>
       
         
          <tr  style=" background-color: lightpink  !important;;">
             <td >اجمالي الاستقطاعات</td>
             <td style="text-align: right;">{{ $data['total_debit']*1}}</td>
          </tr>
       </table>
         <br>
         <table   dir="rtl"    cellspacing="1" cellpadding="3" border="2"    style="text-align:right;border-color: black; width: 97%;  margin: 0 auto;">
            <tr style=" background-color: yellow ;">
               <td style="width: 30%;"> صافي المرتب</td>
               <td style="text-align: right;">
                  <p>
                     @if($data['final_the_net_befor_close'] >0)
                     مدين ب ({{ $data['final_the_net_befor_close']*1 }}) جنيه  
                     @elseif ($data['final_the_net_befor_close'] <0)
                     دائن ب ({{ $data['final_the_net_befor_close']*1*(-1) }})   جنيه
                     @else
                     متزن
                     @endif
                  </p>
               </td>
            </tr>
            <tr style=" background-color: yellow ;">
               <td style="width: 30%;">  حالة اغلاق وارشفة الراتب</td>
               <td style="text-align: right;">
                  @if($data['is_closed']==1)
                  تم اغلاق وأرشفة المرتب
                  <br>    وحالة المرتب بعد الاغلاق
                  @if($data['final_the_net'] >0)
                  مدين ب ({{ $data['final_the_net']*1 }}) جنيه  
                  @elseif ($data['final_the_net'] <0)
                  دائن ب ({{ $data['final_the_net']*1*(-1) }})   جنيه
                  @else
                  متزن
                  @endif
                @php 
                  $dt=new DateTime($data['closed_at']);
                  $date=$dt->format("Y-m-d");
                  $time=$dt->format("h:i");
                  $newDateTime=date("A",strtotime($data['closed_at']));
                  $newDateTimeType= (($newDateTime=='AM')?'صباحا ':'مساء'); 
                  @endphp <br>
                 بواسطة {{ $data['closed_by_admin'] }}
                  {{ $date }} <br>
                  {{ $time }}
                  {{ $newDateTimeType }}  <br>
              

                  @else
                  مازال مفتوح
                  @endif

               </td>
            </tr>
         </table>
         <p style="text-align: left; padding-left:20%;font-weight: bold;font-size: 14px; color: black;"><?=date('d-m-Y');?></p>
         <!------------------------------------------------------------------->
         @else
         <div class="alert alert-danger">
            عفوا لاتوجد بيانات لعرضها !!
         </div>
         @endif
      </div>
      <script>
        // window.print();
      </script>
   </body>
</html>