<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">

        <div class="main-card overflow-auto">

    <div class="flex justify-between items-center">
        <p class="title-2">Editar grupo de filas</p>
        <a href="{{ route('bundles.home') }}" class="btn"><i class="fa-solid fa-arrow-left me-2"></i>Voltar</a>
    </div>

    <hr class="my-4">

    <div class="flex justify-between gap-10">

        <div class="w-full">

            <form action="{{ route('bundle.edit.submit') }}" method="post">

                @csrf

                <input type="hidden" name="queues_list" value="{{ old('queues_list', json_encode($bundle_queue_list, true)) }}" >
                 <input type="hidden" name="bundle_id" value="{{ Crypt::encrypt($bundle->id) }}" >

                <div class="mb-4">
                    <label for="bundle_name" class="label">Nome do grupo</label>
                    <input type="text" id="bundle_name" name="bundle_name" class="input w-full" placeholder="Nome do bundle" value="{{ old('bundle_name',$bundle->name)}}">
                    {!! ShowValidationError('bundle_name',$errors)  !!}
                </div>

                <div class="flex justify-between gap-4">
                    <div class="mb-4 w-full">
                      <p class="label">Credencial user</p>
                      <p class="text-slate-400">{{ $bundle->credential_username }}</p>    
                    </div>
                
                </div>

                <div class="mb-4">
                    <p class="title-3 mb-2">Filas de espera do grupo </p>
                    <div id="div_queues" class="main-card !bg-slate-100 !p-4"></div>
                </div>
                {!! ShowValidationError('queues_list',$errors) !!}

                <button type="submit" class="btn"><i class="fa-solid fa-check me-2"></i>Editar grupo</button>

            </form>

        </div>




        <div class="w-full">
            <p class="text-slate-600 font-bold">Filas de espera</p>
            @if($queues->count() === 0)
              <p class="text-slate-400 text-center mt-12">Não existem filas de espera</p>
            @else 
              <table id="table-queues">
                 <thead class="bg-black text-white">
                    <tr>
                        <th></th>
                        <th>Nome:</th>
                        <th>Serviço:</th>
                        <th>Balcão:</th>
                        <th>Estado</th>
                        <th>Pre-visualização</th>
                    </tr>
                 </thead>
                 <tbody>
                     @foreach($queues as $queue)
                        <tr>
                           {{-- 
                               foram adicionados alguns atributos nesse botão que idenficam a 
                               fila que eu quero adicionar, esses atributos serão usados no javascript
                               para adicionar a fila na lista de filas do grupo  
                            --}}
                            <td>
                                <button class="btn btn-queue"
                                  data-queue-hash-code="{{ $queue->hash_code }}"
                                  data-queue-name="{{ $queue->name }}">
                                 <i class="fa-solid fa-circle-plus"></i>
                                </button>
                            </td>
                            <td>{{ $queue->name }}</td>
                            <td>{{ $queue->service_name }}</td>
                            <td>{{ $queue->service_desk }}</td>
                            <td><span class="me-2" > {!! getQueueStateIcon($queue->status) !!}</span>{!! getQueuetStateText($queue->status) !!} </td>
                            <td>{!! getQueuePreview($queue) !!}</td>
                        </tr>
                     @endforeach
                 </tbody>
              </table> 

              @endif
        </div>




    </div>

</div>

<script>

     $(document).ready(function(){

            $('#table-queues').DataTable({
               //traduzindo os componetes pra portugues

               language:{
                  url:"{{ asset('assets/datatables/pt-PT.json') }}"
              }
            }
        );
          
        });

        /*
          a definição da lista de fila do grupo vai começar pela parte visual
          devido a velocidade de processamento, depois que tudo estiver definido 
          visualmente, ai sim eu atualizo o input hidden que vai enviar a lista
          de filas para o backend, pra economizar processamento no servidor 
        */ 

        let queues = getQueueListFromInputHidden();

        renderQueues(queues);

        document.querySelectorAll('.btn-queue').forEach( btn => {

            btn.addEventListener('click', function(){

                const queueHashCode = this.getAttribute('data-queue-hash-code');

                const queueName = this.getAttribute('data-queue-name');

                //verificar se a fila já está na lista

                if(queues.some(queue => queue.hash_code === queueHashCode)){
                    queues = queues.filter( queue => queue.hash_code !== queueHashCode);
                }else{
                    queues.push({
                        hash_code: queueHashCode,
                        name: queueName
                    });
                }

                renderQueues(queues);

         });

        });

        function renderQueues(queues){

            let html = '';

            if(queues.length === 0 ){

                html='<p class="text-center text-slate-400"> Não existem filas no grupo</p>';
            }else{
                queues.forEach( queue => {
                    html+='<div class="flex bg-white justify-between items-center p-2 mb-1 rouded-lg border-gray-300">';
                    html+=`<span class="font-bold">${queue.name}</span><i class="fa-regular text-red-500 cursor-pointer fa-trash-can"  onclick="deleteFromQueue('${queue.hash_code}')"></i>`;
                    html+='</div>';
                });


            }

            document.querySelector('#div_queues').innerHTML = html;

            document.querySelector('input[name="queues_list"]').value = JSON.stringify(queues);
}

        function deleteFromQueue(hash_code){
            queues = queues.filter( q => q.hash_code !== hash_code);
            renderQueues(queues);
        }

        function getQueueListFromInputHidden(){

            const queueListInput = document.querySelector('input[name = "queues_list"]');

            if(queueListInput){
                try {

                    return JSON.parse(queueListInput.value);

                } catch (e) {

                     return [];
                    
                }
            }

            return [];

        }

        


</script>

</x-layouts.auth-layout>