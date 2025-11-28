<x-layouts.auth-layout subtitle="{{ $subtitle }}">
<div class="main-card overflow-auto">

    <div class="flex justify-between items-center">
        <p class="title-2">Criar nova fila de espera</p>
        <a href="#" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
    </div>

    <hr class="my-4">

    <div class="flex gap-4">

        <div class="w-1/2">

            <form action="{{ route('queue.create.submit') }}" method="POST" novalidate>

                @csrf 

                <input type="hidden" id="hidden_hash_code" name="hidden_hash_code" value="" >

                <div class="mb-4">
                    <label for="name" class="label">Nome da fila</label>
                    <input type="text" name="name" id="name" class="input w-full" placeholder="Nome da fila">
                </div>

                <div class="mb-4">
                    <label for="description" class="label">Descrição</label>
                    <input type="text" name="description" id="description" class="input w-full" placeholder="Descrição da fila">
                </div>

                <div class="flex gap-4 mb-4">
                    <div class="w-1/2">
                        <label for="service" class="label">Serviço</label>
                        <input type="text" name="service" id="service" class="input w-full" placeholder="Serviço">
                    </div>

                    <div class="w-1/2">
                        <label for="desk" class="label">Balcão de atendimento</label>
                        <input type="text" name="desk" id="desk" class="input w-full" placeholder="Balcão de atendimento">
                    </div>
                </div>

                <div class="flex gap-4 mb-4">

                    <div class="w-full">
                        <label for="prefix" class="label">Prefixo</label>

                        <select name="prefix" id="prefix" class="input w-full">
                             <option value="-">sem prefixo</option>

                            @php 
                              $prefixes = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                            @endphp
                           
                            @foreach($prefixes as $prefix)
                               
                               <option value="{{ $prefix }}" {{ $prefix ==='A' ? 'selected' :'' }} >{{ $prefix }}</option>
                                                                 
                            @endforeach
                        </select>



                    </div>

                    <div class="w-full">
                        <label for="total_digits" class="label">Total de dígitos</label>
                        <select name="total_digits" id="total_digits" class="input w-full">
                            <option value="2" selected>00</option>
                            <option value="3">000</option>
                            <option value="4">0000</option>
                        </select>
                    </div>

                    <div class="w-full">
                        <label for="status" class="label">Estado</label>
                        <select name="status" id="status" class="input w-full">
                            <option value="active" selected>Ativa</option>
                            <option value="inactive">Inativa</option>
                        </select>
                    </div>

                </div>

                <div class="mb-4">
                    <p class="label">Código de hash</p>
                    <div class="flex gap-2">
                        <p class="input bg-slate-100 w-full" id="hash_code">&nbsp;</p>
                        <button type="button" id="btn_hash_code" class="btn"><i class="fa-solid fa-rotate"></i></button>
                    </div>
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
                            <input type="text" class="input text-zinc-900" name="color_1" id="color_1" value="#0d3561">
                        </div>
                        <div>
                            <label class="label">Prefixo - Cor do texto</label>
                            <input type="text" class="input text-zinc-900" name="color_2" id="color_2" value="#ffffff">
                        </div>
                    </div>

                    <div class="w-1/2">
                        <div class="mb-4">
                            <label class="label">Número - Cor de fundo</label>
                            <input type="text" class="input text-zinc-900" name="color_3" id="color_3" value="#adb4b9">
                        </div>
                        <div>
                            <label class="label">Número - Cor do texto</label>
                            <input type="text" class="input text-zinc-900" name="color_4" id="color_4" value="#011020">
                        </div>
                    </div>

                </div>

                <button type="submit" class="btn"><i class="fa-solid fa-check me-2"></i>Criar nova fila</button>

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

    

     Coloris({el:'#color_1',alpha:false, swatches:fixedColors, defaultColor:'0d3561'});
     Coloris({el:'#color_2',alpha:false, swatches:fixedColors, defaultColor:'0d3561'});
     Coloris({el:'#color_3',alpha:false, swatches:fixedColors, defaultColor:'0d3561'});
     Coloris({el:'#color_4',alpha:false, swatches:fixedColors, defaultColor:'0d3561'});

     //capturando os elementos para montar uma iteração

     const prefix = document.querySelector("#prefix");
     const total_digits = document.querySelector("#total_digits");
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
            totalDigits: parseInt(total_digits.value),
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

     prefix.addEventListener('change',updateTicketPreview);
     total_digits.addEventListener('change',updateTicketPreview);
     color1.addEventListener('change',updateTicketPreview); 
     color2.addEventListener('change',updateTicketPreview); 
     color3.addEventListener('change',updateTicketPreview); 
     color4.addEventListener('change',updateTicketPreview); 

     function getHashCode(){

        //vou  urr o fetch pra acionar a rota 
        //e pagar o resultado que seria a hash 

        fetch("{{ route('queue.generate.hash') }}")

          //depois de requisitar eu coloco o conteudo da resposta dentro de um json 

             .then(response => response.json())
             
          //mas na resposta não vem so a hash ela é um objeto com varios dados
          //eu passo esses dados pra variavel data  
             .then(data=>{

                //aqui eu seleciono o input que mostra a hash pelo id 
                //depois eu pego o conteudo de texto dele e adiono somente a hash do objeto 

                document.querySelector("#hash_code").textContent = data.hash;
                document.querySelector('input[name="hidden_hash_code"]').value = data.hash;


                //eu coloquei esse catch aqui no caso de falha mas essa url so falha se o servidor estiver offline 
                //então nesse caso a pagina nem seria carregada 

             })
             .catch(error =>{
                 alert('Aconteceu um erro ao gerar a hash !');
             });
     }


     getHashCode();

     //gerando uma nova hash com o clique do botão 
     
     document.querySelector('#btn_hash_code').addEventListener('click',getHashCode);

   </script>

</x-layouts.auth-layout>
