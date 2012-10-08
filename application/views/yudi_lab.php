<?php
$this->load->view('_shared/header');
$this->load->view('_shared/menus');
?>
<h5>EXIST CHECK</h5>
<p>Check data exist, jika exist maka akan memanggil function lain</p>
<div id="exist_check" class="form-horizontal">
    <div class="control-group">
        <div class="controls">
            <?php
            echo form_input(array(
                'name' => 'no_polisi',
                'class' => 'input-small',
                'autocomplete' => 'off',
                'placeholder' => 'NO POLISI',
                'style' => 'text-transform : uppercase'
                    )
            );
            ?>
            <p class="help-block"><?php echo form_error('no_polisi') ?></p>
        </div>
    </div>
    <div class="control-group">
            <div class="controls">
                <?php
                echo form_input(array(
                    'name' => 'perbaikan',
                    'placeholder' => 'PERBAIKAN LAIN_LAIN',
                    'autocomplete' => 'off',
                    'style' => 'text-transform : uppercase'
                        )
                )
                ?>
                <p class="help-block"><?php echo form_error('lain_lain') ?></p>
            </div>
        </div>
    <div class="control-group">
        <label class="control-label">Merk :</label>
        <div class="controls">
            <label id="merk" class="control-label al" style="width:400px">-</label>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">Jenis :</label>
        <div class="controls">
            <label id="jenis" class="control-label al" style="width:400px">-</label>
        </div>
    </div>
</div>
<p>Usage:</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="pln"></span></li></ol></pre>
<hr>
<h5>TYPEAHEAD BOOTSTRAP CUSTOM</h5>
<p>Autosuggestion modifikasi typeahead bootstrap.</p>
<div id="typeahead_custom" class="form-horizontal">
    <div class="control-group">
        <div class="controls">
            <?php
            echo form_input(array(
                'name' => 'bengkel',
                'class' => 'input-medium',
                'autocomplete' => 'off',
                'placeholder' => 'BENGKEL REKANAN',
                'style' => 'text-transform : uppercase'
                    )
            )
            ?>
            <p class="help-block"><?php echo form_error('bengkel') ?></p>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label">Kota :</label>
        <div class="controls">
            <label id="kota" class="control-label al" style="width:400px">-</label>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label">PIC / Email / Telepon :</label>
        <div class="controls">
            <label id="pic_email_telephone" class="control-label al" style="width:400px">-</label>
        </div>
    </div>
</div>
<p>Usage:</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="pln">$('#typeahead_custom input[name="bengkel"]').typeahead({
                url : site_url+"transaction/service_luar/suggestion",
                objCatch : 'bengkel_options', 
                dataToView : {
                    kota_options : '#kota',
                    pic_options : '#pic'
                }
            });</span></li></ol></pre>
<hr>
<h5>AUTOCOMPLETE</h5>
<p>Autocomplete dengan menggunakan enter, kemudian men-grab semua data-data bawaan json.</p>
<div id="autocomplete" class="form-horizontal">
    <input name="no_polisi" value="" autocomplete ="off" class="input-small" style="text-transform: uppercase" placeholder="no polisi">
    <p>Result will be applied to :</p>
    <input name="vehicle_id" placeholder="vehicle id" value="" autocomplete ="off" class="input-small" style="text-transform: uppercase">
    <input name="vehicle_type" placeholder="vehicle type" value="" autocomplete ="off" class="input-small" style="text-transform: uppercase">

    <div class="control-group">
        <label class="control-label">Peminjam :</label>
        <label id="peminjam" class="control-label al" style="width:400px">000</label>
    </div>
    <div class="control-group">
        <label class="control-label">Tgl. Sewa :</label>
        <label id="tgl_sewa" class="control-label al">000</label>
    </div>
    <div class="control-group">
        <label class="control-label">Hide me :</label>
        <label id="hide_show_me" class="control-label al">hide me</label>
    </div>
</div>
<p>Usage:</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="pln">$(<span class="str">'input[name="no_polisi"]'</span>).<span class="kwd">completeby13</span>({
            <span class="kwd">url</span> : <span class="atn">site_url</span>+"vehicle/complete_info/json",
            <span class="kwd">apply</span> :{
                <span class="atn">id</span> : <span class="str">'input[name="vehicle_id"]'</span>,
                <span class="atn">jenis</span> : <span class="str">'input[name="vehicle_type"]'</span>
            },
            <span class="kwd">where</span> : {
                <span class="atn">no_rangka</span> : <span class="str">'MH1JF9112CK692022'</span>,
                <span class="atn">no_bpkb</span> : <span class="str">'i06858138'</span>
            },
            <span class="kwd">xalert</span> : {
                <span class="atn">last_pickup_id</span> : <span class="str">'|Kendaraan tidak disewa'</span>
            },
            <span class="kwd">hidesome</span> : {
                <span class="atn">foc</span> : <span class="str">'false|input[name="vehicle_type"]'</span>
            }
        });</span></li></ol></pre>
<hr>
<h2>Using bootstrap-alert.js</h2>
<p>Enable dismissal of an alert via javascript:</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="pln">$</span><span class="pun">(</span><span class="str">".alert"</span><span class="pun">).</span><span class="pln">alert</span><span class="pun">()</span></li></ol></pre>
<h3>Markup</h3>
<p>Just add <code>data-dismiss="alert"</code> to your close button to automatically give an alert close functionality.</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;a</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"close"</span><span class="pln"> </span><span class="atn">data-dismiss</span><span class="pun">=</span><span class="atv">"alert"</span><span class="pln"> </span><span class="atn">href</span><span class="pun">=</span><span class="atv">"#"</span><span class="tag">&gt;</span><span class="pln">&amp;times;</span><span class="tag">&lt;/a&gt;</span></li></ol></pre>
<h3>Methods</h3>
<h4>$().alert()</h4>
<p>Wraps all alerts with close functionality. To have your alerts animate out when closed, make sure they have the <code>.fade</code> and <code>.in</code> class already applied to them.</p>
<h4>.alert('close')</h4>
<p>Closes an alert.</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="pln">$</span><span class="pun">(</span><span class="str">".alert"</span><span class="pun">).</span><span class="pln">alert</span><span class="pun">(</span><span class="str">'close'</span><span class="pun">)</span></li></ol></pre>
<h3>Events</h3>
<p>Bootstrap's alert class exposes a few events for hooking into alert functionality.</p>
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th style="width: 150px;">Event</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>close</td>
            <td>This event fires immediately when the <code>close</code> instance method is called.</td>
        </tr>
        <tr>
            <td>closed</td>
            <td>This event is fired when the alert has been closed (will wait for css transitions to complete).</td>
        </tr>
    </tbody>
</table>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="pln">$</span><span class="pun">(</span><span class="str">'#my-alert'</span><span class="pun">).</span><span class="pln">bind</span><span class="pun">(</span><span class="str">'closed'</span><span class="pun">,</span><span class="pln"> </span><span class="kwd">function</span><span class="pln"> </span><span class="pun">()</span><span class="pln"> </span><span class="pun">{</span></li><li class="L1"><span class="pln">  </span><span class="com">// do something…</span></li><li class="L2"><span class="pun">})</span></li></ol></pre>

<?php $this->load->view('_shared/footer'); ?>