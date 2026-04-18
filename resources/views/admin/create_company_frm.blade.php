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
                {!! ShowValidationError('company_logo',$errors)  !!}

                <p class="text-sm text-red-500 italic" id="error_message"></p>

                <div class="flex justify-center mt-8">
                     <img id="logo_preview" src="#" alt="Logo" class="hidden w-[200] h-[200] border-slate-300">
                </div>


            </div>

            <div class="main-card w-2/3">

                <div class="mb-4">
                    <label for="company_name" class="label">Nome do cliente</label>
                    <input type="text" name="company_name" id="company_name" class="input w-full" value="{{ old('company_name') }}" />
                    {!! ShowValidationError('company_name',$errors)  !!}

                </div>

                <div class="mb-4">
                    <label for="address" class="label">Endereço</label>
                    <input type="text" name="address" id="address" class="input w-full" value="{{ old('address')}}" />
                    {!! ShowValidationError('address',$errors)  !!}
                </div>

                <div class="flex gap-4 mb-4">

                    <div class="w-1/3">
                        <label for="phone" class="label">Telefone</label>
                        <input type="text" name="phone" id="phone" class="input w-full" value="{{ old('phone') }}" />
                        {!! ShowValidationError('phone',$errors)  !!}
                    </div>

                    <div class="w-2/3">
                        <label for="email" class="label">Email</label>
                        <input type="email" name="email" id="email" class="input w-full" value="{{ old('email') }}" />
                         {!! ShowValidationError('email',$errors)  !!}

                    </div>

                </div>

                <div class="mb-4">

                    <label for="status" class="label">Estado</label>
                        <select class="input w-1/3" id="status">

                            <option value="active" {{ old('status') === 'active' ? 'selected': '' }}>Ativo</option>

                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected': '' }}>Inativo</option>
                            
                        </select>

                    {!! ShowValidationError('status',$errors)  !!}
                </div>

                <hr class="my-6">

                <div class="w-2/3 mb-4">
                    <p class="text-2xl text-slate-900">Defina o usuário administrador.</p>
                    <p class="text-sm text-slate-400 mb-4">Será enviado um email com as instruções para a conclusão do registo.</p>
                    <label for="admin_email" class="label">Email do administrador cliente</label>
                    <input type="email" name="admin_email" id="admin_email" class="input w-full" value="{{ old('admin_email') }}" />
                     {!! ShowValidationError('admin_email',$errors)  !!}

                </div>

                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check me-2"></i>Criar cliente</button>

            </div>

        </div>

    </form>

</div>

<script>

 /*   document.querySelector("#company_logo").addEventListener('change',function(event){

        const error_message = document.querySelector("#error_message");

        error_message.textContent = '';

        const [file] = event.target.files;

        const preview = document.querySelector("#logo_preview");

        if(file){

            const validTypes = ['image/png','image/jpeg'];

            const validExtensions = ['png','jpg','jpeg'];

            const fileType = file.type;

            const fileExtension = file.name.split('.').pop().toLowerCase();


                if(!validTypes.includes(fileType) || !validExtensions.includes(fileExtension)){

                    error_message.textContent = 'Selecione uma imagem JPG ou PNG';

                    event.target.value = '';

                    preview.src = "#";

                    preview.classList.add('hidden');

                    return;

                }

            const img = new Image();

                    img.onload = function(){

                        if(img.width === 200 && img.height === 200){

                            preview.src = URL.createObjectURL(file);
                            preview.classList.remove('hidden');


                        }else{

                            error_message.textContent = 'A imagem deve ter exatamente 200x200 pixeis';

                            event.target.value = '';

                            preview.src = "#";

                            preview.classList.add('hidden');

                           
                        }

                    };

                    img.src = URL.createObjectURL(file);
            
            
        }else{
                     preview.src = "#";

                     preview.classList.add('hidden');

                  
        }
        
    });


*/

</script>




</x-layouts.auth-layout>
