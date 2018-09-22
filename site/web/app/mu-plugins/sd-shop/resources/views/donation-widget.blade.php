<div class="jumbotron">
  <h1 class="display-4">Donation</h1>
  <div class="input-group lead">
    <span class="input-group-addon">£</span>
    <input type="text" class="form-control donate-amount" aria-label="Amount" value="{{ $context->price() }}">
    <span class="input-group-btn">
      <button class="snipcart-add-item buy-button donate-button"
          data-item-id="{{ $context->id() }}"
          data-item-name="{{ $context->title() }}"
          data-item-price="{{ $context->price() }}"
          data-item-url="{{ $context->url() }}"
          data-item-description="{{ $context->description()}}"
          data-item-stackable="false"
          data-item-shippable="false"
        >
          Donate
      </button>
      <button class="test-button">Test</button>
    </span>
  </div>
</div>
