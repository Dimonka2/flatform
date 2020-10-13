<div x-data @tags-update="console.log('tags updated', $event.detail.tags)" data-tags='["aaa","bbb"]' class="ff-dd-wrap">
    <div x-data="tagSelect()" x-init="init('parentEl')" @click.away="clearSearch()" @keydown.escape="clearSearch()">
      <div class="position-relative" @keydown.enter.prevent="addTag(textInput)">
        <input x-model="textInput" x-ref="textInput" @input="search($event.target.value)"
            class="form-input border rounded w-100 py-2 px-3 ff-dd-input" placeholder="Enter some tags">
        <div :class="[open ? 'd-block' : 'd-none']">
          <div class="mt-2 w-100" style="position: absolute; z-order: 40; left: 0; ">
            <div class="py-1 bg-white rounded shadow border ff-dd-popup">
              <a @click.prevent="addTag(textInput)" class="d-block py-1 px-5 ff-popup-element">Add tag "<span class="font-semibold" x-text="textInput"></span>"</a>
            </div>
          </div>
        </div>
        <!-- selections -->
        <template x-for="(tag, index) in tags">
          <div class="ff-dd-tag rounded mt-2 mr-1">
            <span class="ml-2 mr-1 leading-relaxed truncate max-w-xs" x-text="tag"></span>
            <button @click.prevent="removeTag(index)" class="ff-dd-tag-close">
              <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" width="1.5rem" height="1.5rem" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z"/></svg>
          </button>
          </div>
        </template>
      </div>
    </div>
  </div>

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

  @push('js')
    <script>
        function tagSelect() {
            return {
                open: false,
                textInput: '',
                tags: [],
                init() {
                    this.tags = JSON.parse(this.$el.parentNode.getAttribute('data-tags'));
                },
                addTag(tag) {
                    tag = tag.trim()
                    if (tag != "" && !this.hasTag(tag)) {
                        this.tags.push( tag )
                    }
                    this.clearSearch()
                    this.$refs.textInput.focus()
                    this.fireTagsUpdateEvent()
                },
                fireTagsUpdateEvent() {
                    this.$el.dispatchEvent(new CustomEvent('tags-update', {
                        detail: { tags: this.tags },
                        bubbles: true,
                    }));
                },
                hasTag(tag) {
                    var tag = this.tags.find(e => {
                        return e.toLowerCase() === tag.toLowerCase()
                    })
                    return tag != undefined
                },
                removeTag(index) {
                    this.tags.splice(index, 1)
                    this.fireTagsUpdateEvent()
                },
                search(q) {
                    if ( q.includes(",") ) {
                        q.split(",").forEach(function(val) {
                        this.addTag(val)
                        }, this)
                    }
                    this.toggleSearch()
                },
                clearSearch() {
                    this.textInput = ''
                    this.toggleSearch()
                },
                toggleSearch() {
                    this.open = this.textInput != ''
                }
            }
        }
    </script>
  @endpush
