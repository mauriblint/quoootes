<div class="container">
    <!-- <div class="row">
        <div v-for="template in templates" class="col-3">
            <img :src="template.preview_url" class="img-fluid" @click="onEditTemplate(template)">
        </div>
    </div> -->

    <!--  Templates -->
    <div class="row" v-if="page==='templates'">
        <div class="col-12">
            <div class="d-flex justify-content-between">
                <h1 class="mb-4">Templates</h1>
                <div>
                    <a class="btn btn-primary" target="_blank" href="/studio?action=create_template">Create</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th class="text-right" scope="col" style="width: 100%;">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="template in templates">
                                <td class="text-left"><img style="width: 120px;" :src="template.preview_url" @click="onEditTemplate(template)"></td>
                                <td>Otto</td>
                                <td class="text-right">
                                    <button class="btn btn-link" @click="onEditTemplate(template)">Edit</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!--  Backgrounds -->
    <div class="row" v-if="page==='backgrounds'">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="">Backgrounds</h1>
                <div>
                    <button class="btn btn-primary">Upload</button>
                    <input type="file" name="files" @change="onImagesSelected($event)" accept="image/jpeg, image/png, image/gif, image/svg+xml" multiple>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col"></th>
                                <th class="text-right" scope="col" style="width: 100%;">Handle</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="bg in backgrounds">
                                <td class="text-left"><img style="width: 120px;" :src="bg.url" @click="onEditTemplate(bg)"></td>
                                <td></td>
                                <td class="text-right">
                                    <button class="btn btn-link" @click="onDeleteBackground(bg)">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


</div>