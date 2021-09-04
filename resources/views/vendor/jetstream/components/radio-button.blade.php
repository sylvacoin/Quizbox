<div class="inline-block radio " >
    <input
        name="{{ $name  }}"
        type="radio"
        id="{{ $id  }}"
        class="hidden"
        value="{{ $option  }}"
        data-id="{{ $dataId }}"
    />
    <label for="{{ $id }}" class="answer px-2 py-1 rounded-lg flex justify-center items-center text-xl w-10 h-10">
        {{ $option }}
    </label>
</div>
