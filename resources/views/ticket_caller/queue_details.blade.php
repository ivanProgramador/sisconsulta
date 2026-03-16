<x-layouts.auth-layout subtitle="{{ empty($subtitle) ? 'Bundles' : $subtitle }}">
   <div class="main-card overflow-auto">
       <div class="flex justify-between items-center">
          <p class="title-3">{{ $queue->description }}</p>
          <p class="title-3">{{ $queue->description }}</p>
       </div>
       <hr class="my-4">

       <div class="flex gap-6 mb-4">
          <p class="title-3" >Serviço: <strong>{{ $queue->service_name}}</strong></p>
          <p class="title-3" >Balcão: <strong>{{ $queue->service_desk}}</strong></p>
       </div>


               <div class="flex justify-center gap-6">
                  
                     <div class="w-1/4 rounded-xl border-1 border-slate-400 text-center p-4" >
                           <p class="title-3">Última senha chamada:</p>
                           @if(empty($lastTicket))
                              <p class="text-slate-400 my-4">Não existe senha anterior</p>
                           @else 
                              <p class="text-6xl font-bold">{{ $lastTicket->queue_ticket_number }}</p>
                              <p>Chamada em:<strong> {{ $lastTicket->queue_ticket_called_at }} </strong></p>
                              <p class="text-2xl" id="time_last_ticket"></p>
                           @endif
                     </div>




                     <div class="w-1/4 rounded-xl border-1 border-slate-400 text-center p-4">
                           <p class="title-3">Próxima senha:</p>
                           @if(empty($nextTicket)) 
                              <p class="text-slate-400 my-4">Não existe próxima senha</p>
                           @else 
                              <p class="text-6xl font-bold">{{ $nextTicket->queue_ticket_number }}</p>
                              <p>Criada em:<strong> {{ $nextTicket->queue_ticket_created_at }} </strong></p>
                              <p class="text-2xl" id="time_next_ticket"></p>
                           @endif
                     </div>

                     <div class="flex justify-center items-center w-1/4 rounded-xl border-1 border-slate-400 text-center p-4">

                         @if(empty($nextTicket))
                            <span class="btn opacity-20 cursor-none !p-4" >Chamar</span>
                         @else 
                             <a href="{{ route('caller.queue.caller',
                                         [
                                          'queue_id', => 0000
                                          'ticket_id' => 0000
                                         ]
                                   )
                            }}" class="btn !p-4 !text-4xl">Chamar</a>
                         @endif
                        
                     </div>

          
               </div>

      <script>

         @if(!empty($lastTicket))
           
          let lastTicketTime = new Date({{ \Carbon\Carbon::parse($lastTicket->queue_ticket_called_at)->timestamp * 1000 }});
           
           updateLastTicketTime();
           
           setInterval(() => {

             updateLastTicketTime();

           }, 1000);

         @endif


          @if(!empty($nextTicket))
           
           let nextTicketTime = new Date({{ \Carbon\Carbon::parse($nextTicket->queue_ticket_created_at)->timestamp * 1000 }});
           
           updateNextTicketTime();
           
           setInterval(() => {

             updateNextTicketTime();

           }, 1000);

         @endif







         //tempo do ultimo ticket ============================================================================================================================

          function updateLastTicketTime(){

             let now = new Date();
             let diff = Math.floor((now - lastTicketTime) / 1000);
             let hours = Math.floor((diff / 3600));
             let minutes = Math.floor((diff % 3600) / 60);
             let seconds = diff % 60;
             document.querySelector("#time_last_ticket").innerText = `${hours} h ${minutes} min. ${seconds} s`;
            
          }


          //============================================================================================================================

             function updateNextTicketTime(){

             let now = new Date();
             let diff = Math.floor((now - nextTicketTime) / 1000);
             let hours = Math.floor((diff / 3600));
             let minutes = Math.floor((diff % 3600) / 60);
             let seconds = diff % 60;
             document.querySelector("#time_next_ticket").innerText = `${hours} h ${minutes} min. ${seconds} s`;
            
          }



         

         
      </script>



</x-layouts.auth-layout >
