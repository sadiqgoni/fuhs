{{--<h6 class="text-center text-dark">KEEP RECORD OF ANNUAL INCREMENT HISTORY</h6>--}}
<style>
    sup{
        color:orangered;
    }
</style>

        <?php
        $allowances = \App\Models\Allowance::where('status', 1)->get();
        $deductions = \App\Models\Deduction::where('status', 1)->get();
        ?>
        
        <tr>
            <td  style="margin-top: 40px;">Basic Sal: <br> Sal Arrears: </td>
            <td style="text-align: right"> {{round($paySlip->basic_salary,2)}} <br>{{number_format($paySlip->salary_areas,2)}}</td>
            <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>

            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2"><b>Allowances</b></td>
            <td>&nbsp;</td>
            <td colspan="2"><b>Deductions</b></td>
        </tr>

        @php
           $max = max($allowances->count(), $deductions->count());
        @endphp
        
        @for($i=0; $i<$max; $i++)
           <tr>
               @if(isset($allowances[$i]))
                  <td>{{ $allowances[$i]->allowance_name }}:</td>
                  <td style="text-align: right">{{ number_format($paySlip->{'A'.$allowances[$i]->id}, 2) }}</td>
               @else
                  <td></td><td></td>
               @endif

               <td>&nbsp;</td>

               @if(isset($deductions[$i]))
                  <td>{{ $deductions[$i]->deduction_name }}:</td>
                  <td style="text-align: right">{{ number_format($paySlip->{'D'.$deductions[$i]->id}, 2) }}
                      <sup>@if(array_key_exists('D'.$deductions[$i]->id, $loan)){{$loan['D'.$deductions[$i]->id]}}@endif</sup>
                  </td>
               @else
                   <td></td><td></td>
               @endif
           </tr>
        @endfor

        <tr>
            <td style="font-weight: bolder" colspan="4"><b>Gross Pay:{{number_format($paySlip->gross_pay,2)}}</b></td>
        </tr>
        <tr>
            <td style="font-weight: bolder" colspan="4"><b>Total Ded:{{number_format($paySlip->total_deduction,2)}}</b></td>
        </tr>
        <tr>
            <td style="font-weight: bolder" colspan="4"><b>Net Pay: {{number_format($paySlip->net_pay,2)}}</b></td>

        </tr>
        </tbody>
    </table>
@empty
    No record found
@endforelse

