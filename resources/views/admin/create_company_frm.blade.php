<x-layouts.auth-layout subtitle="{{ $subtitle }}">

    <div class="main-card overflow-auto">

    <div class="flex justify-between">
        <p class="title-3">Novo cliente</p>
        <a href="{{ route('admin.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
    </div>

    <hr class="my-4">

    <form action="#" method="POST" enctype="multipart/form-data">

        @csrf

        <div class="flex gap-6">

            {{-- company logo --}}
            <div class="main-card w-1/3">
                <label for="company_logo" class="label">Logo do cliente</label>
                <input type="file" name="company_logo" id="company_logo" class="input" />
                <p class="text-sm text-red-500 italic" id="error_message"></p>

                {{-- Elemento de preview da imagem --}}

                <div class="flex justify-center mt-8">
                    <img id="logo_preview" src="#" alt="Logo" class="hidden w-[200px] h-[200px] boder-1 border-slate-300">
                </div>


            </div>

            <div class="main-card w-2/3">

                <div class="mb-4">
                    <label for="company_name" class="label">Nome do cliente</label>
                    <input type="text" name="company_name" id="company_name" class="input w-full" />
                </div>

                <div class="mb-4">
                    <label for="address" class="label">Endereço</label>
                    <input type="text" name="address" id="address" class="input w-full" />
                </div>

                <div class="flex gap-4 mb-4">

                    <div class="w-1/3">
                        <label for="phone" class="label">Telefone</label>
                        <input type="text" name="phone" id="phone" class="input w-full" />
                    </div>

                    <div class="w-2/3">
                        <label for="email" class="label">Email</label>
                        <input type="email" name="email" id="email" class="input w-full" />
                    </div>

                </div>

                <div class="mb-4">

                    <label for="status" class="label">Estado</label>
                    <select class="input w-1/3">
                        <option value="active" selected>Ativo</option>
                        <option value="inactive">Inativo</option>
                    </select>

                </div>

                <hr class="my-6">

                <div class="w-2/3 mb-4">
                    <p class="text-2xl text-slate-900">Defina o usuário administrador.</p>
                    <p class="text-sm text-slate-400 mb-4">Será enviado um email com as instruções para a conclusão do registo.</p>
                    <label for="admin_email" class="label">Email do administrador cliente</label>
                    <input type="email" name="admin_email" id="admin_email" class="input w-full" />
                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Criar cliente</button>

            </div>

        </div>

    </form>

</div>

<script>
    
    //para criar um comportamento de preview eu vou precisa manipular o elemento 
    //responsável por esse comportamento usnado javascript 
     

    document.querySelector("#company_logo").addEventListener('change',function(event){
       
        //caso aconteça um erro  eu colocco ele nessa constante e a mesnagem aparece na tela 

        const error_message = document.querySelector("#error_message");
        
        //depois de mostrar o erro o usuario vai corrigir e eu limpo a mensagem

        error_message.textContent ='';


        //um imput do tipo file pode rreceber um arquivo só ou uma coleção deles
        //aqui estou me assegurando que vou pegar somente o primeiro

        const [file] = event.target.files;
        const preview = document.querySelector("#logo_preview");

        //para evitar que aruivos invalidos sejam carrregados é necessarios executar uma serie de validações 

        if(file){
            
            const validTypes = ['image/png','image/jpeg'];
            const validExtensions = ['png','jpg','jpeg'];
            const fileType = file.type;

            //nessa linha eu pego o nome coompleto do arquivo e uso o split pra separar ele
            //usando o . como parametro
            /*
              se o nome do arquivo for

               empresa01.png

              o split('.') cria um array

               ['empresa01','png']   

            depois o pop() pega o ultimo indice do array
               
                'png'

            mas existe o risco da extensão estar em caixa alta então eu uso o " toLowerCase() "
            pra tornar a extensão minuscula parar que al possa ser lida e avaliada 
            */  

            const fileExtension = file.name.split('.').pop().toLowerCase();

            if(!validTypes.includes(fileType) || !validExtensions.includes(fileExtension)){

                error_message.textContent = "Imagem invalida: selecione uma imagem do tipo png ,jpg ou jpeg";
                event.target.value='';
                preview.src = "#";
                preview.classList.add('hidden');
                return; 
            }

            const img = new Image();

            img.onload = function () {

                if(img.width === 200 && img.height === 200){

                    preview.src = URL.createObjectULR(file);
                    preview.classList.remove('hidden'); 

                }else{
                    errorMessage.textContent = "A imagem deve ter exatamente 200x200 pixeis";
                    event.target.value='';
                    preview.src = "#";
                    preview.classList.add('hidden');
                    return; 
                }
            };

           img.src = preview.src = URL.createObjectURL(file);
           }else{

             preview.src ="#";
             preview.classList.add('hidden');
              
           }
        }
    );

<script>


</x-layouts.auth-layout>
