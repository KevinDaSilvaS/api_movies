# api_movies
Api rest entirely made in php to manage movies,actors and directors.Test made for the php developer role at magic web design

<h3>Iniciando o projeto:</h3>
<p>Basta apenas fazer o download do repositório e rodar o aqrquivo .sql dentro do Mysql 
com os dados do arquivo config.php na pasta do IvoryV1 e o projeto ja está funcionando</p>

<h3>Processo de Desenvolvimento:</h3>
<ul>
  <li>
    <p>Devido ao meu pouco contato com o framework Laravel e devido ao tempo (onde só pude reservar o sabado para o desenvolvimento da    solução) infelizmente não foi possivel uma pesquisa mais aprofundada sobre o processo de criação de APIS com o Laravel e a fiz utilizando a linguagem em sua forma mais purista com o auxilio de OOP,PDO e um mini ORM que estou desenvolvendo no meu tempo livre.</p>
   </li>
  <li>
    <p>Acredito que durante o processo realmente pequei em manter algumas partes do meu código DRY(dont repeat yourself e acabei repetindo códigos de validação em algumas requests em uma situação com mais tempo teria desaclopado em um arquivo só para validações).</p>
    </li>
    <li>
     <p>No desenvolvimento da api de filmes notei a necessidade de criar classes auxiliares porque meu código estava ficando gigantesco   o que facilitou algumas validações e operações além de melhorar a legibilidade.Queria ter obtido tempo de aplicar algum pattern organizacional melhor como um strategy ou algo do genero.</p>
    </li>
  <li>
    <p>Acredito que numa situação ideal deveria ter criado uma classe com um crud para cada tabela para facilitar o processo das consultas e não precisar faze-las no momento em que se recebe a request</p>
    </li>
  
</ul>

<h3>Tecnologias Usadas:</h3>
<p>PHP7,Mysql e insomnia</p>

<h3>Conclusão:</h3>
<p>Concluí que para o curto periodo de tempo a solução ficou funcional porém na realidade é que realmente gostaria de ter feito mais em quesitos de engenharia e principalmente de ter ido mais a fundo no uso do laravel e implementado a solução conforme todas as especificações.</p>

<span>Agradeço pela oportunidade de realizar o teste e fico muito grato por terem entrado em contato comigo.</span>
