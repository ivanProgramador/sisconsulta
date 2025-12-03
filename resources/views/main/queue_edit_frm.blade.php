<x-layouts.auth-layout subtitle="{{ empty('subtitle') ? '' : $subtitle }}">


<div class="main-card overflow-auto">

    <div class="flex justify-between items-center">
        <p class="title-2">Editar fila de espera</p>
        <a href="{{ route('home')}}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
    </div>

    <hr class="my-4">

    <div class="flex gap-4">

        <div class="w-1/2">

            <form action="{{ route('queue.edit.submit') }}" method="POST" novalidate>

                @csrf 

                <input type="hidden" name="queue_id" value="{{ Crypt::encrypt($queue->id) }} " >

                <div class="mb-4">
                    <label for="name" class="label">Nome da fila</label>
                    <input type="text" name="name" id="name" class="input w-full" placeholder="Nome da fila" value="{{ old('name',$queue->name )}}">

                     {!! ShowValidationError('name',$errors)  !!}
                     {!! ShowServerError()  !!}
                </div>

                <div class="mb-4">
                    <label for="description" class="label">Descrição</label>
                    <input type="text" name="description" id="description" class="input w-full" placeholder="Descrição da fila" value="{{ old('description',$queue->description)}}">

                    {!! ShowValidationError('description',$errors)  !!}
                     {!! ShowServerError()  !!}
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="w-1/2">
                        <label for="service" class="label">Serviço</label>
                        <input type="text" name="service" id="service" class="input w-full" placeholder="Serviço" value="{{ old('service',$queue->service_name) }}">
                        {!! ShowValidationError('service',$errors)  !!}
                        {!! ShowServerError()  !!}
                    </div>

                    <div class="w-1/2">
                        <label for="desk" class="label">Balcão de atendimento</label>
                        <input type="text" name="desk" id="desk" class="input w-full" placeholder="Balcão de atendimento" value="{{ old('desk',$queue->service_desk) }}" >
                        {!! ShowValidationError('desk',$errors)  !!}
                        {!! ShowServerError()  !!}

                    </div>
                </div>

                <div class="flex gap-4 mb-4">

                    <div class="w-full">
                        <label for="prefix" class="label">Prefixo</label>

                        <select name="prefix" id="prefix" class="input w-full">


                             <option value="-" {{ $queue->queue_prefix ==='-' ? 'selected' :''}} >sem prefixo</option>

                            @php 
                              $prefixes = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                            @endphp
                           
                            @foreach($prefixes as $prefix)
                               
                               <option value="{{ $prefix }}" {{ $prefix === $queue->queue_prefix ? 'selected' :'' }} >{{ $prefix }}</option>
                                                                 
                            @endforeach
                        </select>
                        {!! ShowValidationError('prefix',$errors)  !!}

                    </div>

                  

                    <div class="w-full">
                        <label for="status" class="label">Estado</label>
                        <select name="status" id="status" class="input w-full">
                            <option value="active" {{ $queue->status === 'active' ? 'selected':'' }}>Ativa</option>
                            <option value="inactive" {{ $queue->status === 'inactive' ? 'selected':'' }}>Inativa</option>
                            <option value="done" {{ $queue->status === 'done' ? 'selected':'' }} >Encerrada</option>
                        </select>
                          {!! ShowValidationError('status',$errors)  !!}
                    </div>

                </div>

                <div class="mb-4">
                    <p class="label">Código de hash</p>
                    <div class="flex gap-2">
                        <p class="input bg-slate-100 w-full" id="hash_code">{{ $queue->hash_code }}</p>
                        
                    </div>
                      {!! ShowValidationError('hidden_hash_code',$errors)  !!}
                </div>




                <div class="main-card flex !p-4 mb-4">

                    {{---
                       Nessa parte existe um aponto importante a ser abordado
                       uma agrande maioria dos usuarios não sabe usar codigos hexadecimais 
                       para configurar a cores então eu adicionei a biblioteca coloris 
                       que gera uma mini palheta onde ele pode esclehr de forma visual 
                       a cor que ele deseja no ticket da fila    
                    
                    --}}

                    <div class="w-1/2">
                        <div class="mb-4">
                            <label class="label">Prefixo - Cor de fundo</label>
                            <input type="text" class="input text-zinc-900" name="color_1" id="color_1" value="{{ old('color_1',$queueColors['prefix_bg_color']) }}">
                             {!! ShowValidationError('color_1',$errors)  !!}
                        </div>
                        <div>
                            <label class="label">Prefixo - Cor do texto</label>
                            <input type="text" class="input text-zinc-900" name="color_2" id="color_2" value="{{ old('color_2',$queueColors['prefix_text_color']) }}">
                            {!! ShowValidationError('color_2',$errors)  !!}
                        </div>
                    </div>

                    <div class="w-1/2">
                        <div class="mb-4">
                            <label class="label">Número - Cor de fundo</label>
                            <input type="text" class="input text-zinc-900" name="color_3" id="color_3" value="{{ old('color_3',$queueColors['number_bg_color']) }}">
                            {!! ShowValidationError('color_3',$errors)  !!}
                        </div>
                        <div>
                            <label class="label">Número - Cor do texto</label>
                            <input type="text" class="input text-zinc-900" name="color_4" id="color_4" value="{{ old('color_4',$queueColors['number_text_color']) }}">
                            {!! ShowValidationError('color_4',$errors)  !!}
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn"><i class="fa-solid fa-check me-2"></i>Salvar alterações</button>

            </form>

        </div>

        <div class="flex w-1/2 justify-center items-center">
            <div id="color_preview" class="flex main-card !bg-slate-200">
                <p id="example_prefix" class="rounded-tl-2xl rounded-bl-2xl text-center text-9xl font-bold p-6" style="background-color: #0d3561; color: #ffffff;">A</p>
                <p id="example_number" class="rounded-tr-2xl rounded-br-2xl text-center text-9xl font-bold p-6" style="background-color: #adb4b9; color: #011020;">01</p>
            </div>
        </div>

    </div>

</div>


<script>

     // integrando o coloris nos elementos necessários
     //a coloris no caso dese porjeto eu vou usar apenas 3 configurações
     //mas ela tem bem mais possibilidades  
     
     /*
       a funcção coloris nesse caso vai receber um objeto com  3 dados 
       
       el: é o elemento que desejo que receba uma palheta de cores eu pasei o id  
       alpha: eu coloquei o valor false porque eu não quero que o usuario possa mexer na transparencia das cores 
       defaultColor: é uma cor padrão que fica na palheta 
     */

     /*
       Em alguns casos o usuario não vai querer usar paleta porque pode levar tempo 
       ate encontrar a cor certa para colocar no ticket da fila então o coloris 
       tambem fornece a porssibilidade de colocar core fixas pre-definidas
       usando a diretiva 
       
       swatches
       
       Nisso eu posso definir uma grupo de cores fixas qie vão ficar disponiveis para o usuario escolher  

     */

     const fixedColors = [
                '#ff0000',
                '#660000',
                '#0000ff',
                '#000066',
                '#00ff00',
                '#006600',
                '#ffa800',
                '#aa6600',
                '#ffff00',
                '#666600',
                '#000000',
                '#ffffff',
    ];

    /*
     #od3561
     #ffffff
     #adb4b9
     #011020
    
    */

    

     Coloris({el:'#color_1',alpha:false, swatches:fixedColors, defaultColor:'{{ old('color_1',$queueColors['prefix_bg_color'])   }}'});
     Coloris({el:'#color_2',alpha:false, swatches:fixedColors, defaultColor:'{{ old('color_2',$queueColors['prefix_text_color']) }}'});
     Coloris({el:'#color_3',alpha:false, swatches:fixedColors, defaultColor:'{{ old('color_3',$queueColors['number_bg_color'])   }}'});
     Coloris({el:'#color_4',alpha:false, swatches:fixedColors, defaultColor:'{{ old('color_4',$queueColors['number_text_color']) }}'});

     //capturando os elementos para montar uma iteração

     const prefix = document.querySelector("#prefix");
     const total_digits = {{ $queue->queue_total_digits }};
     const color1 = document.querySelector("#color_1");
     const color2 = document.querySelector("#color_2");
     const color3 = document.querySelector("#color_3");
     const color4 = document.querySelector("#color_4");
     
     //elementos da preview do ticket
     const example_prefix = document.querySelector("#example_prefix");
     const example_number = document.querySelector("#example_number");

     //função que atualiza o elemento de preview
     
     function updateTicketPreview(){
        
        //constante que vai pegar todos os atributos 
        // do elemento visual do ticket 

        const ticketProperties = {
            hasPrefix: prefix.value !== '-',
            prefix: prefix.value,
            totalDigits: total_digits,
            prefixBackgroundColor: color1.value,
            prefixTextColor: color2.value,
            numberBackGroundColor: color3.value,
            numberTextColor: color4.value,
        };

        //atualizando o prefixo
        if(ticketProperties.hasPrefix){
            example_prefix.textContent = ticketProperties.prefix;
            example_prefix.style.backgroundColor = ticketProperties.prefixBackgroundColor;
            example_prefix.style.color = ticketProperties.prefixTextColor;
            example_prefix.classList.remove('hidden'); 
            example_number.classList.remove('hidden');
            example_prefix.classList.add('rounded-tl-2xl','rounded-bl-2xl');
        }else{
             example_prefix.classList.add('hidden');
        } 

        //atualizando o numero 
        example_number.textContent = String(1).padStart(ticketProperties.totalDigits,'0');
        example_number.style.backgroundColor = ticketProperties.numberBackGroundColor;
        example_number.style.color = ticketProperties.numberTextColor;





       }

    updateTicketPreview();

     prefix.addEventListener('change',updateTicketPreview);
     color1.addEventListener('change',updateTicketPreview); 
     color2.addEventListener('change',updateTicketPreview); 
     color3.addEventListener('change',updateTicketPreview); 
     color4.addEventListener('change',updateTicketPreview); 

     
     //chamada inicial
     updateTicketPreview();

     
     
     
     
    

     
   </script>


</x-layouts.auth-layout>
