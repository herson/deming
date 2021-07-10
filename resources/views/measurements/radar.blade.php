@extends("layout")

@section("title")
Radar
@endsection

@section("content")

<div class="grid">    
    <div class="row">
        <div class="cell-9">

    @foreach($domains as $domain)
    <div class="row">
        <div class="cell-10">
            <b>{{ $domain->title }} - {{ $domain->description }}</b>
        </div>

        @if ($loop->first)
               
        <div class="cell-2" valign="right">
            <form action="/measurement/radar">
            <input type="text" 
                    data-role="calendarpicker" 
                    name="cur_date" 
                    value="{{$cur_date}}"
                    data-input-format="%Y-%m-%d"                    
                    onchange="this.form.submit()">            
            </form>
        </div>

        @endif

    </div>
    <div class="row">
        <div class="cell-4">
            <br>
                <canvas id="canvas-radar-{{ $domain->id }}" width="100" height="100"></canvas>
        </div>
        <div class="cell-8">
            <table class="table table-bordered">
                  <thead>
                  <tr>
                    <th>Note</th>
                    <th><center>#</center></th>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Next</th>
                  </tr>
                  </thead>
                  <tbody>
            @foreach($measurements as $measurement)
                @if ($measurement->domain_id == $domain->id)
                    <tr>
                        <td><center>
                    @if ($measurement->score==1)
                        &#128545;
                    @elseif ($measurement->score==2)
                        &#128528;
                    @elseif ($measurement->score==3)
                        <span style="filter: sepia(1) saturate(5) hue-rotate(80deg)">&#128512;</span>
                    @else
                        &#9899;
                    @endif
                        </center></td>

                    <td><a href="/controls/{{ $measurement->control_id }}">{{ $measurement->clause }}</a></td>
                    <td>{{ $measurement->name }}</td>
                    <td><a href="/measurements/{{ $measurement->measurement_id }}">{{ $measurement->realisation_date }}</a></td>
                    <td><a href="/measurements/{{ $measurement->next_id }}">{{ $measurement->next_date }}</a></td>
                    </tr>                    
                @endif
            @endforeach
        </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

</div>

<script src="/vendors/chartjs/Chart.bundle.min.js"></script>
<script src="/js/utils.js"></script>

    <script>

    var color = Chart.helpers.color;

    var options = {
        responsive: true,
        legend: {
            display: false,
        },
        title: {
            display: false
        }
    };

@foreach($domains as $domain)


    var ctx_{{ $domain->id }} = document.getElementById('canvas-radar-{{ $domain->id }}').getContext('2d');

    var marksData_{{ $domain->id }} = {
      labels: [
            @foreach ($measurements as $m) 
                @if ($m->domain_id==$domain->id)
                    '{{ $m->clause }}'
                    {{ $loop->last ? '' : ',' }}
                @endif
            @endforeach 
            ],
      datasets: [
        {
        // blue
        backgroundColor: color(window.chartColors.blue).alpha(0.9).rgbString(),
        borderColor: window.chartColors.blue,
        pointBackgroundColor: window.chartColors.blue,
        data: [
        @foreach ($measurements as $m) 
            @if ($m->domain_id==$domain->id) 
                @if ($m->score==1)
                    .5
                @elseif ($m->score==2)
                    1.5
                @elseif ($m->score==3)
                    2.5
                @else
                    0
                @endif
            {{ $loop->last ? '' : ',' }}  
            @endif
        @endforeach 
        ]
      },{        
       // red
        backgroundColor: color(window.chartColors.red).alpha(0.3).rgbString(),
        borderColor: window.chartColors.red,
        pointBackgroundColor: window.chartColors.red,        
        data: [
        @foreach ($measurements as $m) 
            @if ($m->domain_id==$domain->id) 
                1
            {{ $loop->last ? '' : ',' }}  
            @endif
        @endforeach 
        ]
      },{
        // orange
        backgroundColor: color(window.chartColors.orange).alpha(0.3).rgbString(),
        borderColor: window.chartColors.orange,
        pointBackgroundColor: window.chartColors.orange,
        data: [
        @foreach ($measurements as $m) 
            @if ($m->domain_id==$domain->id) 
                2
            {{ $loop->last ? '' : ',' }}  
            @endif
        @endforeach 
        ]
      },{
        // green
        backgroundColor: color(window.chartColors.green).alpha(0.3).rgbString(),
        borderColor: window.chartColors.green,
        pointBackgroundColor: window.chartColors.green,
        data: [
        @foreach ($measurements as $m) 
            @if ($m->domain_id==$domain->id) 
                3
            {{ $loop->last ? '' : ',' }}  
            @endif
        @endforeach 
        ]
      },
       {
        // label: "Zero",
        backgroundColor: "rgba(0,0,0,1)",
        data: [0,0,0,0]
      } 
      ]
    };
         
    var radarChart_{{ $domain->id }} = new Chart(ctx_{{ $domain->id }}, {
      type: 'radar',
      data: marksData_{{ $domain->id }},
      options: options
    });
@endforeach

</script>



@endsection