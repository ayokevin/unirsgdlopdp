import { React, useEffect, useState } from 'react'
import { fetchJson } from '../../hooks/useFetchJson';
import Modal from '../Modal/Modal';
import Select from 'react-select';
import { Notify } from '../Modal/Notify';


export const UpdateFollowUp = ({ selectedDataRow, closeModalEdit }) => {

    const [selectedDataApply, setSelectedDataApply] = useState({ value: selectedDataRow.article_apply, label: selectedDataRow.article_apply_name });
    const [selectedDataReference, setSelectedDataReference] = useState({ value: selectedDataRow.status_id, label: selectedDataRow.article_status_name });
    const [selectedDataCompliance, setSelectedDataCompliance] = useState({ value: selectedDataRow.article_compliance, label: selectedDataRow.article_compliance_name });
    const [apply, setApply] = useState([]);
    const [references, setReferences] = useState([]);
    const [compliance, setCompliance] = useState([]);

    const [data, setData] = useState({

        articleId: selectedDataRow.article_id,
        _articleApply: selectedDataRow.article_apply,
        articleObservation: selectedDataRow.article_observation,
        _statusId: selectedDataRow.status_id,
        articleJustification: selectedDataRow.article_justification,
        _articleCompliance: selectedDataRow.article_compliance,
    });
    const [showNotifyError, setNotifyError] = useState(false);
    const [showNotifySuccess, setNotifySuccess] = useState(false);

    const fetchDataReference = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.article',
                referenceField: 'status_id',
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiReference/apiReference.php/listReference',
                requestData
            );

            setReferences(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    const fetchDataCompliance = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.article',
                referenceField: 'article_compliance',
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiReference/apiReference.php/listReference',
                requestData
            );

            setCompliance(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    const fetchDataApply = async () => {
        try {
            const requestData = {
                referenceTableName: 'system.article',
                referenceField: 'article_apply',
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiReference/apiReference.php/listReference',
                requestData
            );

            setApply(responseData.data);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        fetchDataCompliance();
        fetchDataApply();
        fetchDataReference();
    }, []);

    const handleUpdate = async () => {

        if (data._articleApply === '' || data.articleObservation === '' || data._statusId === '' || data.articleJustification === '' || data._articleCompliance === '') {
            handleShowError();
            return;
        }

        try {

            const requestData = {

                articleId: selectedDataRow.article_id,
                _articleApply: data._articleApply,
                articleObservation: data.articleObservation,
                _statusId: data._statusId,
                articleJustification: data.articleJustification,
                _articleCompliance: data._articleCompliance
            };

            const responseData = await fetchJson(
                'http://localhost:3000/app/apiArticle/apiArticle.php/updateArticle',
                requestData
            );

            handleShowSuccess();
            setTimeout(() => {
                closeModalEdit();
            }, 2000);

        } catch (error) {
            console.error(error);
        }
    };

    const handleChangeJustification = (event) => {
        setData({ ...data, articleJustification: event.target.value });
    };

    const handleChangeObservation = (event) => {
        setData({ ...data, articleObservation: event.target.value });
    };


    const optionsApply = apply.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const optionsReference = references.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));

    const optionsCompliance = compliance.map((data) => ({
        value: data.reference_id,
        label: data.reference_name,
    }));


    const handleChangeApply = (event) => {
        setSelectedDataApply(event || '');
        if (event && event.value) {
            setData({ ...data, _articleApply: event.value });
        }
    };

    const handleChangeCompliance = (event) => {
        setSelectedDataCompliance(event || '');
        if (event && event.value) {
            setData({ ...data, _articleCompliance: event.value });
        }
    };

    const handleChangeStatus = (event) => {
        setSelectedDataReference(event || '');
        if (event && event.value) {
            setData({ ...data, _statusId: event.value });
        }
    };

    const handleShowError = () => {
        setNotifyError(true);
        setTimeout(() => {
            setNotifyError(false);
        }, 5000);
    };

    const handleShowSuccess = () => {
        setNotifySuccess(true);
        setTimeout(() => {
            setNotifySuccess(false);
        }, 2000);
    };

    return (
        <Modal
            title="Editar articulo"
            body={
                <div className="card card-primary ">
                    <div className="card-header">
                        <h3 className="card-title">Datos del articulo</h3>
                        {showNotifyError && <Notify message="Todos los campos son obligatorios" type="error" />}
                        {showNotifySuccess && <Notify message="Datos guardados" type="success" />}
                    </div>
                    <div className="card-body ">
                        <form>
                            <div className="row">
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Articulo Id</label>
                                        <input
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.article_id}
                                            disabled
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Nombre</label>
                                        <textarea
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.article_name}
                                            placeholder="Enter ..."
                                            style={{ height: "150px" }}
                                            disabled={true}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-6">
                                    <div className="form-group">
                                        <label>Detalle</label>
                                        <textarea
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.article_description}
                                            placeholder="Enter ..."
                                            style={{ height: "150px" }}
                                            disabled={true}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Justificación</label>
                                        <textarea
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.article_justification}
                                            onChange={handleChangeJustification}
                                            placeholder="Enter ..."
                                            style={{ height: "150px" }}
                                        />
                                    </div>
                                </div>
                                <div className="col-sm-12">
                                    <div className="form-group">
                                        <label>Observación</label>
                                        <textarea
                                            type="text"
                                            className="form-control"
                                            defaultValue={selectedDataRow.article_observation}
                                            onChange={handleChangeObservation}
                                            placeholder="Enter ..."
                                            style={{ height: "150px" }}
                                        />
                                    </div>
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Aplica</label>
                                    <Select
                                        onChange={handleChangeApply}
                                        options={optionsApply}
                                        placeholder={'Seleccione una opción'}
                                        defaultValue={selectedDataApply}
                                    ></Select>
                                </div>
                                <div className="form-group col-sm-6">
                                    <label>Estado</label>
                                    <Select
                                        onChange={handleChangeStatus}
                                        options={optionsReference}
                                        placeholder={'Seleccione un estado'}
                                        defaultValue={selectedDataReference}
                                    ></Select>
                                </div>
                                <div className="form-group col-sm-12">
                                    <label>Nivel de cumplimiento</label>
                                    <Select
                                        onChange={handleChangeCompliance}
                                        options={optionsCompliance}
                                        placeholder={'Seleccione un nivel'}
                                        defaultValue={selectedDataCompliance}
                                    ></Select>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            } // Aquí puedes pasar el contenido que desees
            button1Name="Cerrar"
            button2Name="Guardar Cambios"
            handleButton1Click={closeModalEdit}
            handleButton2Click={handleUpdate}
            closeModalEdit={closeModalEdit}
        />
    )
}






