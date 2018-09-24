<div class="jumbotron">
  <h1 class="display-4">Donation</h1>
  <div class="input-group lead">
    <span class="input-group-addon">£</span>
    <input type="text" class="form-control donate-amount" aria-label="Amount" value="{{ $price }}">
    <span class="input-group-btn">
      <button class="snipcart-add-item buy-button donate-button"
          data-item-id="{{ $id }}"
          data-item-name="{{ $title }}"
          data-item-price="{{ $price }}"
          data-item-url="{{ $url }}"
          data-item-description="{{ $description}}"
          data-item-stackable="false"
          data-item-shippable="false"
        >
          Donate
      </button>
    </span>
  </div>
</div>
