<div wire:model.debounce.500ms='input' x-data @tags-update="console.log('tags updated', $event.detail.tags)" data-tags='["aaa","bbb"]' class="ff-dd-wrap">
    <div x-data="tagSelect()" x-init="init(textInput)" @click.away="$dispatch('input', '');textInput='';" @keydown.escape="$dispatch('input', '');textInput='';">
      <div class="position-relative" @keydown.enter.prevent="$wire.addTag(textInput);textInput='';">
        <input x-model="textInput" x-ref="textInput" id="dd-input"
            class="form-input border rounded w-100 py-2 px-3 ff-dd-input" placeholder="Enter some tags">
        <div class="{{$input ? 'd-block' : 'd-none' }}">

                <ul class="list-group dropdown-menu d-flex mt-0 p-0 shadow">
                    @forelse ($choices as $key => $item)
                        <li class="list-group-item p-2">
                            <button wire:click.prevent="addChoice({{$key}})" class="py-1 px-2 ff-dd-item ">{{$item}}</button>
                        </li>
                    @empty
                        Nothing to select...
                    @endforelse

                </ul>
        </div>
        <!-- selections -->
        @foreach ($selected as $key => $item)
            <div class="ff-dd-tag rounded mt-2 mr-1" id="selected-{{$key}}">
                <span class="ml-2 mr-1 leading-relaxed truncate max-w-xs">{{$item}}</span>
                <button @click.prevent="$wire.removeItem({{$key}})" class="ff-dd-tag-close">
                <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
            </button>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  @push('js')
    <script>
        function tagSelect() {
            return {
                open: false,
                textInput: '',
                init(textInput) {
                    this.tags = JSON.parse(this.$el.parentNode.getAttribute('data-tags'));

                    this.open = this.textInput != '';
                    console.log('init', this);
                },
                search(q) {
                },

            }
        }
    </script>
  @endpush

  @push('css')
  <style>
      .ff-dd-wrap {
          max-width: 32rem;
          margin: 1.5rem;
      }
      .ff-dd-input {
          /* text-gray-700 leading-tight focus:outline-none focus:shadow-outline */
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          line-height:1.25;
          color: #4a5568;
          overflow: visible;
          border-style: solid;
          border-color: #e2e8f0;
          box-sizing: border-box;
      }

      .ff-dd-input:focus {

      }

      .ff-dd-popup {
          /* border-gray-300 */
          border-color: #e2e8f0;
      }

      .ff-popup-element:hover {
          color: #FFF !important;
          background-color: rgb(45, 45, 107);
          cursor: pointer;
      }

      .ff-dd-tag {
          font-size: .875rem;
          align-items: center;
          display: inline-flex;
          background-color: #ebf4ff;
      }

      .ff-dd-item {
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          background-color: transparent;
          padding: 0;
          width: 1.5rem;
          vertical-align: middle;
          border-style: none;
      }

      .ff-dd-tag-close {
          appearance: none;
          -webkit-appearance: none;
          -moz-appearance: none;
          padding: 0;
          width: 1.5rem;
          vertical-align: middle;
          color: #a0aec0;
          height: 2rem;
          display: inline-block;
          padding: 0;
          cursor: pointer;
          background-color: transparent;
          text-transform: none;
          overflow: visible;
          border-style: none;
      }
  </style>
@endpush
