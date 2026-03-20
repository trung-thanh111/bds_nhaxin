 <div class="uk-search uk-flex uk-flex-middle mr20">
     <div class="input-group">
         <input type="text" name="keyword" value="{{ request('keyword') ?: old('keyword') }}"
             placeholder="Nhập từ khóa tìm kiếm..." class="form-control">
         <span class="input-group-btn">
             <button type="submit" name="search" value="search" class="btn btn-primary">Tìm Kiếm
             </button>
         </span>
     </div>
 </div>
