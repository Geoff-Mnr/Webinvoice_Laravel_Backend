<!DOCTYPE html>
<html>
<head>

    <title>{{$documenttype->name}} - {{$document->reference_number}}</title>
    <style>

@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap');

        *,
::before,
::after {
  box-sizing: border-box;
  /* 1 */
  border-width: 0;
  /* 2 */
  border-style: solid;
  /* 2 */
  border-color: #e5e7eb;
  /* 2 */
}

::before,
::after {
  --tw-content: '';
}


html {
  line-height: 1.5;
  /* 1 */
  -webkit-text-size-adjust: 100%;
  /* 2 */
  -moz-tab-size: 4;
  /* 3 */
  tab-size: 4;
  /* 3 */
  font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
  /* 4 */
  font-feature-settings: normal;
  /* 5 */
  font-variation-settings: normal;
  /* 6 */
}

body {
  margin: 0;
  /* 1 */
  line-height: inherit;
  /* 2 */

  
}


hr {
  height: 0;
  /* 1 */
  color: inherit;
  /* 2 */
  border-top-width: 1px;
  /* 3 */
}


abbr:where([title]) {
  -webkit-text-decoration: underline dotted;
          text-decoration: underline dotted;
}


h1,
h2,
h3,
h4,
h5,
h6 {
  font-size: inherit;
  font-weight: inherit;
}


a {
  color: inherit;
  text-decoration: inherit;
}


b,
strong {
  font-weight: bolder;
}



code,
kbd,
samp,
pre {
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
  /* 1 */
  font-size: 1em;
  /* 2 */
}



small {
  font-size: 80%;
}


sub,
sup {
  font-size: 75%;
  line-height: 0;
  position: relative;
  vertical-align: baseline;
}

sub {
  bottom: -0.25em;
}

sup {
  top: -0.5em;
}


table {
  text-indent: 0;
  /* 1 */
  border-color: inherit;
  /* 2 */
  border-collapse: collapse;
  /* 3 */
}


button,
input,
optgroup,
select,
textarea {
  font-family: inherit;
  /* 1 */
  font-feature-settings: inherit;
  /* 1 */
  font-variation-settings: inherit;
  /* 1 */
  font-size: 100%;
  /* 1 */
  font-weight: inherit;
  /* 1 */
  line-height: inherit;
  /* 1 */
  color: inherit;
  /* 1 */
  margin: 0;
  /* 2 */
  padding: 0;
  /* 3 */
}

/*
Remove the inheritance of text transform in Edge and Firefox.
*/

button,
select {
  text-transform: none;
}

/*
1. Correct the inability to style clickable types in iOS and Safari.
2. Remove default button styles.
*/

button,
[type='button'],
[type='reset'],
[type='submit'] {
  -webkit-appearance: button;
  /* 1 */
  background-color: transparent;
  /* 2 */
  background-image: none;
  /* 2 */
}

/*
Use the modern Firefox focus style for all focusable elements.
*/

:-moz-focusring {
  outline: auto;
}

/*
Remove the additional `:invalid` styles in Firefox. (https://github.com/mozilla/gecko-dev/blob/2f9eacd9d3d995c937b4251a5557d95d494c9be1/layout/style/res/forms.css#L728-L737)
*/

:-moz-ui-invalid {
  box-shadow: none;
}

/*
Add the correct vertical alignment in Chrome and Firefox.
*/

progress {
  vertical-align: baseline;
}

/*
Correct the cursor style of increment and decrement buttons in Safari.
*/

::-webkit-inner-spin-button,
::-webkit-outer-spin-button {
  height: auto;
}

/*
1. Correct the odd appearance in Chrome and Safari.
2. Correct the outline style in Safari.
*/

[type='search'] {
  -webkit-appearance: textfield;
  /* 1 */
  outline-offset: -2px;
  /* 2 */
}

/*
Remove the inner padding in Chrome and Safari on macOS.
*/

::-webkit-search-decoration {
  -webkit-appearance: none;
}

/*
1. Correct the inability to style clickable types in iOS and Safari.
2. Change font properties to `inherit` in Safari.
*/

::-webkit-file-upload-button {
  -webkit-appearance: button;
  /* 1 */
  font: inherit;
  /* 2 */
}

/*
Add the correct display in Chrome and Safari.
*/

summary {
  display: list-item;
}

/*
Removes the default spacing and border for appropriate elements.
*/

blockquote,
dl,
dd,
h1,
h2,
h3,
h4,
h5,
h6,
hr,
figure,
p,
pre {
  margin: 0;
}

fieldset {
  margin: 0;
  padding: 0;
}

legend {
  padding: 0;
}

ol,
ul,
menu {
  list-style: none;
  margin: 0;
  padding: 0;
}

/*
Reset default styling for dialogs.
*/

dialog {
  padding: 0;
}

/*
Prevent resizing textareas horizontally by default.
*/

textarea {
  resize: vertical;
}

/*
1. Reset the default placeholder opacity in Firefox. (https://github.com/tailwindlabs/tailwindcss/issues/3300)
2. Set the default placeholder color to the user's configured gray 400 color.
*/

input::placeholder,
textarea::placeholder {
  opacity: 1;
  /* 1 */
  color: #9ca3af;
  /* 2 */
}

/*
Set the default cursor for buttons.
*/

button,
[role="button"] {
  cursor: pointer;
}

/*
Make sure disabled buttons don't get the pointer cursor.
*/

:disabled {
  cursor: default;
}

/*
1. Make replaced elements `display: block` by default. (https://github.com/mozdevs/cssremedy/issues/14)
2. Add `vertical-align: middle` to align replaced elements more sensibly by default. (https://github.com/jensimmons/cssremedy/issues/14#issuecomment-634934210)
   This can trigger a poorly considered lint error in some tools but is included by design.
*/

img,
svg,
video,
canvas,
audio,
iframe,
embed,
object {
  display: block;
  /* 1 */
  vertical-align: middle;
  /* 2 */
}

/*
Constrain images and videos to the parent width and preserve their intrinsic aspect ratio. (https://github.com/mozdevs/cssremedy/issues/14)
*/

img,
video {
  max-width: 100%;
  height: auto;
}

/* Make elements with the HTML hidden attribute stay hidden by default */

[hidden] {
  display: none;
}

*, ::before, ::after{
  --tw-border-spacing-x: 0;
  --tw-border-spacing-y: 0;
  --tw-translate-x: 0;
  --tw-translate-y: 0;
  --tw-rotate: 0;
  --tw-skew-x: 0;
  --tw-skew-y: 0;
  --tw-scale-x: 1;
  --tw-scale-y: 1;
  --tw-pan-x:  ;
  --tw-pan-y:  ;
  --tw-pinch-zoom:  ;
  --tw-scroll-snap-strictness: proximity;
  --tw-gradient-from-position:  ;
  --tw-gradient-via-position:  ;
  --tw-gradient-to-position:  ;
  --tw-ordinal:  ;
  --tw-slashed-zero:  ;
  --tw-numeric-figure:  ;
  --tw-numeric-spacing:  ;
  --tw-numeric-fraction:  ;
  --tw-ring-inset:  ;
  --tw-ring-offset-width: 0px;
  --tw-ring-offset-color: #fff;
  --tw-ring-color: rgb(59 130 246 / 0.5);
  --tw-ring-offset-shadow: 0 0 #0000;
  --tw-ring-shadow: 0 0 #0000;
  --tw-shadow: 0 0 #0000;
  --tw-shadow-colored: 0 0 #0000;
  --tw-blur:  ;
  --tw-brightness:  ;
  --tw-contrast:  ;
  --tw-grayscale:  ;
  --tw-hue-rotate:  ;
  --tw-invert:  ;
  --tw-saturate:  ;
  --tw-sepia:  ;
  --tw-drop-shadow:  ;
  --tw-backdrop-blur:  ;
  --tw-backdrop-brightness:  ;
  --tw-backdrop-contrast:  ;
  --tw-backdrop-grayscale:  ;
  --tw-backdrop-hue-rotate:  ;
  --tw-backdrop-invert:  ;
  --tw-backdrop-opacity:  ;
  --tw-backdrop-saturate:  ;
  --tw-backdrop-sepia:  ;
}

::backdrop{
  --tw-border-spacing-x: 0;
  --tw-border-spacing-y: 0;
  --tw-translate-x: 0;
  --tw-translate-y: 0;
  --tw-rotate: 0;
  --tw-skew-x: 0;
  --tw-skew-y: 0;
  --tw-scale-x: 1;
  --tw-scale-y: 1;
  --tw-pan-x:  ;
  --tw-pan-y:  ;
  --tw-pinch-zoom:  ;
  --tw-scroll-snap-strictness: proximity;
  --tw-gradient-from-position:  ;
  --tw-gradient-via-position:  ;
  --tw-gradient-to-position:  ;
  --tw-ordinal:  ;
  --tw-slashed-zero:  ;
  --tw-numeric-figure:  ;
  --tw-numeric-spacing:  ;
  --tw-numeric-fraction:  ;
  --tw-ring-inset:  ;
  --tw-ring-offset-width: 0px;
  --tw-ring-offset-color: #fff;
  --tw-ring-color: rgb(59 130 246 / 0.5);
  --tw-ring-offset-shadow: 0 0 #0000;
  --tw-ring-shadow: 0 0 #0000;
  --tw-shadow: 0 0 #0000;
  --tw-shadow-colored: 0 0 #0000;
  --tw-blur:  ;
  --tw-brightness:  ;
  --tw-contrast:  ;
  --tw-grayscale:  ;
  --tw-hue-rotate:  ;
  --tw-invert:  ;
  --tw-saturate:  ;
  --tw-sepia:  ;
  --tw-drop-shadow:  ;
  --tw-backdrop-blur:  ;
  --tw-backdrop-brightness:  ;
  --tw-backdrop-contrast:  ;
  --tw-backdrop-grayscale:  ;
  --tw-backdrop-hue-rotate:  ;
  --tw-backdrop-invert:  ;
  --tw-backdrop-opacity:  ;
  --tw-backdrop-saturate:  ;
  --tw-backdrop-sepia:  ;
}

.fixed{
  position: fixed;
}

.bottom-0{
  bottom: 0px;
}

.left-0{
  left: 0px;
}

.table{
  display: table;
}

.h-12{
  height: 3rem;
}

.w-1\/2{
  width: 50%;
}

.w-full{
  width: 100%;
}

.border-collapse{
  border-collapse: collapse;
}

.border-spacing-0{
  --tw-border-spacing-x: 0px;
  --tw-border-spacing-y: 0px;
  border-spacing: var(--tw-border-spacing-x) var(--tw-border-spacing-y);
}

.whitespace-nowrap{
  white-space: nowrap;
}

.border-b{
  border-bottom-width: 1px;
}

.border-b-2{
  border-bottom-width: 2px;
}

.border-r{
  border-right-width: 1px;
}

.border-main{
  border-color: #2563eb;
}

.bg-main{
  background-color: #2563eb;
}

.bg-slate-100{
  background-color: #f1f5f9;
}

.p-3{
  padding: 0.75rem;
}

.px-14{
  padding-left: 3.5rem;
  padding-right: 3.5rem;
}

.px-2{
  padding-left: 0.5rem;
  padding-right: 0.5rem;
}

.py-10{
  padding-top: 2.5rem;
  padding-bottom: 2.5rem;
}

.py-3{
  padding-top: 0.75rem;
  padding-bottom: 0.75rem;
}

.py-4{
  padding-top: 1rem;
  padding-bottom: 1rem;
}

.py-6{
  padding-top: 1.5rem;
  padding-bottom: 1.5rem;
}

.pb-3{
  padding-bottom: 0.75rem;
}

.pl-2{
  padding-left: 0.5rem;
}

.pl-3{
  padding-left: 0.75rem;
}

.pl-4{
  padding-left: 1rem;
}

.pr-3{
  padding-right: 0.75rem;
}

.pr-4{
  padding-right: 1rem;
}

.text-center{
  text-align: center;
}

.text-right{
  text-align: right;
}

.align-top{
  vertical-align: top;
}

.text-sm{
  font-size: 0.875rem;
  line-height: 1.25rem;
}

.text-xs{
  font-size: 0.75rem;
  line-height: 1rem;
}

.font-bold{
  font-weight: 700;
}

.italic{
  font-style: italic;
}

.text-main{
  color: #2563eb;
}

.text-neutral-600{
  color: #525252;
}

.text-neutral-700{
  color: #404040;
}

.text-slate-300{
  color: #cbd5e1;
}

.text-slate-400{
  color: #94a3b8;
}

.text-white{
  color: #fff;
}

@page {
  margin: 0;
}

img{
  max-width: 100%;
  height: 60px;
}

@media print {
  body {
    -webkit-print-color-adjust: exact;
  }
}
    </style>
</head>
<body>
  <div>
    <div class="py-4">
      <div class="px-14 py-6">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-full align-top">
                <div>
                <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('storage/img/wi_logo_light.png'))) }}" alt="logo"/>
                </div>
              </td>

              <td class="align-top">
                <div class="text-sm">
                  <table class="border-collapse border-spacing-0">
                    <tbody>
                      <tr>
                        <td class="border-r pr-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">Date</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">{{ \Carbon\Carbon::parse($document->document_date)->format('d/m/Y') }}</p>
                          </div>
                        </td>
                        <td class="pl-4">
                          <div>
                            <p class="whitespace-nowrap text-slate-400 text-right">{{$documenttype -> name }} #</p>
                            <p class="whitespace-nowrap font-bold text-main text-right">{{$document-> reference_number}}</p>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="bg-slate-100 px-14 py-6 text-sm">
        <table class="w-full border-collapse border-spacing-0">
          <tbody>
            <tr>
              <td class="w-1/2 align-top">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">{{$user->company_name}}</p>
                  <p>TVA: {{$user->vat_number}}</p>
                  <p>Email: {{$user->email}}</p>
                  <p>{{$user->address}}</p>
                  <p>{{$user->zip_code}} {{$user->city}}</p>
                  <p>{{$user->country}}</p>
                </div>
              </td>
              <td class="w-1/2 align-top text-right">
                <div class="text-sm text-neutral-600">
                  <p class="font-bold">{{$customer->company_name}}</p>
                  <p>TVA: {{$customer->vat_number}}</p>
                  <p>Email: {{$customer->email}}</p>
                  <p>{{$customer->billing_address}}</p>
                  <p>{{$customer->billing_zip_code}} {{$customer->billing_state}}</p>
                  <p>{{$customer->billing_country}}</p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <table class="w-full border-collapse border-spacing-0">
          <thead>
            <tr>
              <td class="border-b-2 border-main pb-3 pl-2 font-bold text-main">Produit</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Prix</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Quantité</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">Remise</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-center font-bold text-main">VAT</td>
              <td class="border-b-2 border-main pb-3 pl-2 text-right font-bold text-main">Total</td>
            </tr>
          </thead>
          <tbody>
          @foreach($products as $product)
            <tr>
              <td class="border-b py-3 pl-2">{{$product->name}}</td>
              <td class="border-b py-3 pl-2 text-right">{{$product->selling_price}}</td>
              <td class="border-b py-3 pl-2 text-center">{{$product->pivot->quantity}}</td>
              <td class="border-b py-3 pl-2 text-center">{{$product->pivot->discount}}%</td>
              <td class="border-b py-3 pl-2 text-center">{{$document->price_vvat}}%</td>
              <td class="border-b py-3 pl-2 text-right">{{$product->pivot->price_total}}€</td>
            </tr>
            @endforeach
              <td colspan="7">
                <table class="w-full border-collapse border-spacing-0">
                  <tbody>
                    <tr>
                      <td class="w-full"></td>
                      <td>
                        <table class="w-full border-collapse border-spacing-0">
                          <tbody>
                            <tr>
                              <td class="border-b p-3">
                                <div class="whitespace-nowrap text-slate-400">Net total:</div>
                              </td>
                              <td class="border-b p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-main">{{$document->price_htva}} €</div>
                              </td>
                            </tr>
                            <tr>
                              <td class="p-3">
                                <div class="whitespace-nowrap text-slate-400">Tva :</div>
                              </td>
                              <td class="p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-main">{{$document->price_vvat}} %</div>
                              </td>
                            </tr>
                            <tr>
                              <td class="bg-main p-3">
                                <div class="whitespace-nowrap font-bold text-white">Total:</div>
                              </td>
                              <td class="bg-main p-3 text-right">
                                <div class="whitespace-nowrap font-bold text-white">{{$document->price_tvac}} €</div>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="px-14 text-sm text-neutral-700">
        <p class="text-main font-bold">Détails du paiment</p>
        <p>Le paiement doit être fait pour le :{{ \Carbon\Carbon::parse($document->due_date)->format('d/m/Y') }}</p>
        <p>Sur le numéro de compte: {{implode('.', str_split($user->account_number, 4))}}</p>
        <p>Référence du paiement: {{$document->reference_number}}</p>
      </div>

      <div class="px-14 py-10 text-sm text-neutral-700">
        <p class="text-main font-bold">Notes</p>
        <p class="italic">Merci de bien vouloir régler la facture avant la date d'échéance mentionnée ci-dessus. En cas de retard de paiement, des frais supplémentaires peuvent être appliqués.</p>
        </dvi>

        <footer class="fixed bottom-0 left-0 bg-slate-100 w-full text-neutral-600 text-center text-xs py-3">
          {{$user->company_name}}
          <span class="text-slate-300 px-2">|</span>
          {{$user->email}}
          <span class="text-slate-300 px-2">|</span>
          {{ '+32'.ltrim($user->phone_number, '0') }}
        </footer>
      </div>
    </div>
</body>

</html>
