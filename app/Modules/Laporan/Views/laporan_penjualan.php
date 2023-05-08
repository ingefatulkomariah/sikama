<v-data-table :headers="thPenjualan" :items="dataPenjualanWithIndex" :items-per-page="-1" class="elevation-1" :loading="loading">
    <template v-slot:item="{ item }">
        <tr>
            <td>{{item.index}}</td>
            <td><a :href="'<?= base_url('penjualan'); ?>?faktur=' + item.faktur" title="" alt="">{{item.faktur}}</a></td>
            <td>{{item.jumlah}}</td>
            <td>{{Ribuan(item.subtotal)}}</td>
            <td>{{Ribuan(item.diskon)}}</td>
            <td>{{Ribuan(item.total)}}</td>
            <td>{{Ribuan(item.total_laba)}}</td>
            <td>
                <div v-if="item.id_piutang == null">
                    -
                </div>
                <div v-else>
                    Status: <span v-if="item.status_piutang == 1">Lunas</span><span v-else>Belum Lunas</span><br />
                    Jml.Bayar: {{Ribuan(item.bayar)}}<br />
                    Sisa Piutang: {{Ribuan(item.sisa_piutang)}}<br />
                    Keterangan: {{item.keterangan ?? "-"}}
                </div>
            </td>
            <td>{{item.nama}}</td>
        </tr>
    </template>
    <template slot="body.append">
        <tr>
            <td></td>
            <td class="text-right">Total</td>
            <td>{{ sumTotalPenjualan('jumlah') }}</td>
            <td>{{  Ribuan(sumTotalPenjualan('subtotal')) }}</td>
            <td></td>
            <td>{{ Ribuan(sumTotalPenjualan('total')) }}</td>
            <td>{{ Ribuan(sumTotalPenjualan('total_laba')) }}</td>
            <td>- {{ Ribuan(sumTotalPenjualan('sisa_piutang')) }}</td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td class="text-right">=</td>
            <td><strong>{{ Ribuan(sumTotalPenjualan('total')-sumTotalPenjualan('sisa_piutang')) }}</strong></td>
            <td></td>
            <td></td>
        </tr>
    </template>
    <template v-slot:footer.prepend>
        <v-btn outlined :href="'<?= base_url('laporan/penjualan-pdf') ?>' + '?tgl_start=' + startDate + '&tgl_end=' + endDate" target="_blank" v-show="dataPenjualan != ''">
            <v-icon>mdi-download</v-icon> PDF
        </v-btn>
    </template>
</v-data-table>