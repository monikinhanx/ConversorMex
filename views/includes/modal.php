<section id="modal">
    <div class="modal fade" id="modalContact" tabindex="-1" role="dialog" aria-labelledby="modalContactTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3 class="title">Quer saber mais?</h3>
                    <p>Envie-nos seu contato pelo formulário abaixo:</p>
                    <form action="/?email" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <input value="wendell.maranhao@mexconsulting.com.br" name="toemail" type="text" hidden />
                        </div>
                        <div class="form-group">
                            <input value="contato@mexconsulting.com.br" name="from" type="text" hidden />
                        </div>
                        <div class="form-group">
                            <input value="Contato Site | Mex Consulting" name="subject" type="text" hidden />
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="nome" id="nome" placeholder="Seu nome*" required>
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder="Seu e-mail*" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="telefone" id="telefone" placeholder="Seu celular com DDD*" required/>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="empresa" id="empresa" placeholder="Sua empresa*" required/>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" id="message" name="message" rows="4" placeholder="Sua mensagem"></textarea>
                        </div>
                        <button type="submit" class="btn btn-mex">Enviar</button>
                        <small>*Campo Obrigatório</small>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="modalOk" tabindex="-1" role="dialog" aria-labelledby="modalOkTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Pronto!</h3>
                    <p><?php if(isset($_SESSION['mailresult'])) echo $_SESSION['mailresult'] ?></p>
                    <a class="btn btn-mex" data-dismiss="modal" aria-label="Fechar">OK</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalErro" tabindex="-1" role="dialog" aria-labelledby="modalErroTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h3>Erro!</h3>
                    <p><?php if(isset($_SESSION['ErrorInfo'])) echo $_SESSION['ErrorInfo'] ?></p>
                    <a class="btn btn-mex" data-dismiss="modal" aria-label="Fechar">OK</a>
                </div>
            </div>
        </div>
    </div>
</section>