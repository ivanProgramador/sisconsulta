<x-layouts.guest-layout subtitle="{{ empty($subtitle) ? '' : $subtitle }}">

    <div class="flex flex-col justify-center p-2 h-full">

        <div class="text-end mb-4">
            [opções]
        </div>
        
        @if($bundle->status === 'error'){

            <div class="flex justify-center mt-10">
                <div class="rounded-xl w-1/2 border-1 border-red-800 text-red-800 text-center p-4">
                    <i class="text-3xl fa-solid fa-triangle-exclamation mb-4"></i>
                    <p>Error: {{ $bundle->message }}</p>
                </div>

            </div>



        
        @else 
                    <div class="main-card flex gap-4 w-full">

                    <div class="flex flex-wrap w-full border-1 border-slate-300 rounded-xl">

                        @foreach($bundle->queues as $queue)
                          
                          {{--A peilando um renderização condicional com base na quantidade de filas incluso no grupo --}}
                           <div class="{{ count($bundle->queues) <= 4 ? 'w-1/1' : 'w-1/2' }}">
                               <x-dispenser-queue />
                            </div> 


                        @endforeach
                        
                    </div>

                    <div class="flex w-1/3 h-100 border-1 border-slate-300 rounded-xl p-2">
                            [preview do ticket]
                    </div>
        @endif 



       

            
                
                     
        
        </div>





    </div>

</x-layouts.guest-layout>