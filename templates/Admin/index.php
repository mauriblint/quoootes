<div class="container">
    <!-- <div class="row">
        <div v-for="template in templates" class="col-3">
            <img :src="template.preview_url" class="img-fluid" @click="onEditTemplate(template)">
        </div>
    </div> -->

    <!--  Templates -->
    <div class="row" v-if="page==='templates'">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="">Templates</h1>
                <div>
                    <a class="btn btn-primary" target="_blank" href="/studio?action=create_template">Create</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Preview</th>
                                <th scope="col">Size</th>
                                <th scope="col">Status</th>
                                <th class="text-right" scope="col" style="width: 100%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="template in templates">
                                <td>{{ template.id }}</td>
                                <td class="text-left"><img style="width: 120px;" :src="template.preview_url" @click="onEditTemplate(template)"></td>
                                <td>{{ template.layout }}</td>
                                <td>
                                    <span v-if="template.active" class="badge badge-success">Active</span>
                                    <span v-else class="badge badge-danger">Disabled</span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-warning" @click="onChangeActive(template, 0)" v-if="template.active" data-toggle="tooltip" data-title="Make Inactive"><i class="bi bi-slash-circle"></i></button>
                                        <button type="button" class="btn btn-success" @click="onChangeActive(template, 1)" v-else><i class="bi bi-check-circle" data-toggle="tooltip" data-title="Make Active"></i></button>
                                        <button type="button" class="btn btn-dark" @click="onEditTemplate(template)" data-toggle="tooltip" data-title="Edit"><i class="bi bi-pencil"></i></button>
                                        <button type="button" class="btn btn-dark" @click="onDeleteTemplate(template)" data-toggle="tooltip" data-title="Delete"><i class="bi bi-x-lg"></i></button>
                                    </div>
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
                    <button class="btn btn-primary" @click="onUploadBackgrounds()">Upload</button>
                    <input type="file" ref="fileUploadInput" name="files" @change="onImagesSelected($event)" accept="image/jpeg, image/png, image/gif, image/svg+xml" multiple style="height: 0; opacity: 0; position:fixed; left:-3000px;">
                </div>
            </div>
            <div class="card">
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Preview</th>
                                <th class="text-right" scope="col" style="width: 100%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="bg in backgrounds">
                                <td>{{ bg.id }}</td>
                                <td class="text-left"><img style="width: 120px;" :src="bg.url" @click="onEditTemplate(bg)"></td>
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

    <!--  Designs -->
    <div class="row" v-if="page==='designs'">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="">Designs</h1>
                <div class="d-flex align-items-center">
                    <label class="small mb-0 mr-1">Status</label>
                    <select class="form-control custom-select" v-model="filters.status" @change="fetchDesigns()">
                        <option value="ALL">All</option>
                        <option value="0">Pendings</option>
                        <option value="1">Accepted</option>
                        <option value="2">Rejected</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Showing {{ (designs.length < pagination.per_page) ? designs.length : pagination.per_page }} of {{ pagination.total }} designs
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tag</th>
                                <th scope="col">Size</th>
                                <th scope="col">Status</th>
                                <th class="text-right" scope="col" style="width: 100%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in designs">
                                <td>{{ item.id }}</td>
                                <td class="text-left"><img style="width: 120px;" :src="item.image_url" @click="onItemCicked(item)"></td>
                                <td>
                                    <select v-model="item.tag_id" class="form-control custom-select" style="width: 140px" @change="updateDesignValue(item, 'tag_id', item.tag_id, $event)">
                                        <option :value="tag.id" v-for="tag in tags">{{ tag.name }}</option>
                                    </select>
                                </td>
                                <td>
                                    <span v-if="item.status === 1" class="badge badge-success">Approved</span>
                                    <span v-if="item.status === 0" class="badge badge-warning">Pending</span>
                                    <span v-if="item.status === 2" class="badge badge-danger">Rejected</span>
                                </td>
                                <td class="text-right">
                                    <div class="btn-group" role="group" aria-label="Basic example">
                                        <button type="button" class="btn btn-warning" v-if="item.status !== 2" @click="onChangeDesignStatus(item, 2)" data-toggle="tooltip" data-title="Reject"><i class="bi bi-slash-circle"></i></button>
                                        <button type="button" class="btn btn-success" @click="onChangeDesignStatus(item, 1)" v-if="item.status !== 1" data-toggle="tooltip" data-title="Accept"><i class="bi bi-check-circle"></i></button>
                                        <button type="button" class="btn btn-dark" @click="onEditDesign(item)" data-toggle="tooltip" data-title="Edit"><i class="bi bi-pencil"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <button class="btn btn-success" @click="loadNextPage()" :disabled="filters.page >= pagination.pages">Load more</button>
                </div>
            </div>
        </div>

        <!-- <div v-for="item in designs" class="col-6 col-md-3 mb-3">
            <div class="card">
                <div style="position:relative;">
                    <div class="card-overlay">
                        <i class="bi bi-check-circle-fill text-success" v-if="item.status !== 1" @click="onChangeDesignStatus(item, 1)"></i>
                        <i class="bi bi-dash-circle-fill text-danger" v-if="item.status !== 2" @click="onChangeDesignStatus(item, 2)"></i>
                        <i class="bi bi-pencil text-info" @click="onEditDesign(item)"></i>
                    </div>
                    <img :src="item.image_url" class="card-img-top" />
                </div>
                <div class="card-body">
                    <span v-if="item.status === 1" class="badge badge-success">Approved</span>
                    <span v-if="item.status === 0" class="badge badge-warning">Pending</span>
                    <span v-if="item.status === 2" class="badge badge-danger">Rejected</span>
                </div>
            </div>
        </div> -->
    </div>


</div>