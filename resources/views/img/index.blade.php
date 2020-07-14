@extends('welcome')

@section('content')

    @if(session()->has('message'))
        @if(count(Session::get('trueImg')) > 0)
            <div class="alert alert-success mt-4">
                {{ session()->get('message') }}<br>
                Udało się zapisać <br>
                @foreach(Session::get('trueImg') as $true)
                    {{$true}}
                @endforeach
            </div>
        @endif
        @if(count(Session::get('errorImg')) > 0)
            <div class="alert alert-danger mt-4">
                Nie udało się zapisać 
                @foreach(Session::get('errorImg') as $error)
                    {{$error}}
                @endforeach
            </div>
        @endif
    @endif

    <form method="POST" action="{{url('someurl')}}" style="margin-top: 100px">
        <div class="row">
            <div class="col-sm-12">
                <label for="url">Url address</label>
            </div>
            <div class="form-group col-sm-8">
                <input name="url" type="text" class="form-control" id="url" placeholder="Enter url">
            </div>
            <div class="col-sm-4">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div id="urlAlert" class="alert alert-danger" hidden style="width: 100%; text-align: center">Zły adres url</div>
        </div>
    </form>

    <form method="POST" action="{{url('img/store')}}" class="output__form" style="margin-top: 40px">
        {{csrf_field()}}
        <input type="text" name="urlOutput" hidden>
        <div class="img__container" data-output>

        </div>
        <button type="submit" class="btn btn-warning" hidden>Pobierz wybrane zdjęcia</button>
    </form>

@endsection

@section('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        let btn = document.querySelector('button');
        let outputBtn = document.querySelector('.output__form button');

        let expression = /https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b([-a-zA-Z0-9()@:%_\+.~#?&//=]*)/gi;
        let regex = new RegExp(expression);


        btn.addEventListener('click', (e) => {
            e.preventDefault();
            let input = document.querySelector("input[name='url']").value;
            let urlOutput = document.querySelector("input[name='urlOutput']");
            let output = document.querySelector('[data-output]');
            let location = window.location.href;
            let html = '';

            if(input !== '' && input.match(regex)){

                urlAlert.setAttribute("hidden", "true");

                $.ajax({
                url: `${location}/someurl`,
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    url: input,
                },
                success: function (response) {

                    response.forEach(src => {
                        html += `<div class="img__box"><input type="checkbox" name="check[]" value="${src}" > 
                                 <img src="${src}" class="img"></div>`
                    })

                    output.innerHTML = html;

                    outputBtn.removeAttribute('hidden');
                    urlOutput.setAttribute('value', input)

                    let check = document.querySelectorAll("input[type='checkbox']");
                    let img = document.querySelectorAll(".img");
                    
                    img.forEach((element, index) =>{
                        element.addEventListener('click', ()=>{
                            check[index].checked == false ? check[index].checked = true : check[index].checked = false;
                            element.classList.toggle('check__img');
                        })
                    })
                },
            });
            }else{
                urlAlert.removeAttribute('hidden')
            }


        })

        
    });

</script>
@stop
