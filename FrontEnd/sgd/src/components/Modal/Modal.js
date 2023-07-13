import React from 'react';

const Modal = ({ title, body, button1Name, button2Name, handleButton1Click, handleButton2Click }) => {
    return (
        <div className="modal fade show" id="modal-default"  style={{ display: 'block', overflowX: 'scroll', overflowY: 'scroll' }} aria-modal="true" role="dialog" >
            <div className="modal-dialog">
                <div className="modal-content">
                    <div className="modal-header">
                        <h4 className="modal-title">{title}</h4>
                        <button type="button" className="close" data-dismiss="modal" aria-label="Close" onClick={handleButton1Click}>
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div className="modal-body">{body}</div>
                    <div className="modal-footer justify-content-between">
                        <button type="button" className="btn btn-default" data-dismiss="modal" onClick={handleButton1Click}>
                            {button1Name}
                        </button>
                        <button type="button" className="btn btn-primary" onClick={handleButton2Click}>
                            {button2Name}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Modal;
