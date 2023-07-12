import{k as _,q as y,s as U,r as l,o as f,h as v,j as p,a as d,d as a,e as r,c as $,F as B}from"./app.ca5d1c04.js";const D={class:"grid grid-cols-2 gap-6"},P={class:"col-span-2"},F={class:"col-span-2 sm:col-span-1"},k={class:"col-span-2 sm:col-span-1"},A={class:"col-span-2"},E={name:"TodoForm"},H=Object.assign(E,{setup(b){const u=_(),s={title:"",dueDate:null,dueTime:null,description:""},m="utility/todo/",n=y(m),o=U({...s});return(i,e)=>{const T=l("BaseInput"),c=l("DatePicker"),V=l("BaseEditor"),g=l("FormAction");return f(),v(g,{"init-url":m,"init-form":s,form:o,redirect:"UtilityTodo"},{default:p(()=>[d("div",D,[d("div",P,[a(T,{type:"text",modelValue:o.title,"onUpdate:modelValue":e[0]||(e[0]=t=>o.title=t),name:"title",label:i.$trans("utility.todo.props.title"),error:r(n).title,"onUpdate:error":e[1]||(e[1]=t=>r(n).title=t),autofocus:""},null,8,["modelValue","label","error"])]),d("div",F,[a(c,{modelValue:o.dueDate,"onUpdate:modelValue":e[2]||(e[2]=t=>o.dueDate=t),name:"dueDate",label:i.$trans("utility.todo.props.due_date"),"no-clear":"",error:r(n).dueDate,"onUpdate:error":e[3]||(e[3]=t=>r(n).dueDate=t)},null,8,["modelValue","label","error"])]),d("div",k,[a(c,{modelValue:o.dueTime,"onUpdate:modelValue":e[4]||(e[4]=t=>o.dueTime=t),name:"dueTime",label:i.$trans("utility.todo.props.due_time"),as:"time",error:r(n).dueTime,"onUpdate:error":e[5]||(e[5]=t=>r(n).dueTime=t)},null,8,["modelValue","label","error"])]),d("div",A,[a(V,{id:"Testing",modelValue:o.description,"onUpdate:modelValue":e[6]||(e[6]=t=>o.description=t),name:"description",edit:!!r(u).params.uuid,label:i.$trans("utility.todo.props.description"),error:r(n).description,"onUpdate:error":e[7]||(e[7]=t=>r(n).description=t)},null,8,["modelValue","edit","label","error"])])])]),_:1},8,["form"])}}}),j={name:"TodoAction"},I=Object.assign(j,{setup(b){const u=_();return(s,m)=>{const n=l("PageHeaderAction"),o=l("PageHeader"),i=l("ParentTransition");return f(),$(B,null,[a(o,{title:s.$trans(r(u).meta.trans,{attribute:s.$trans(r(u).meta.label)}),navs:[{label:s.$trans("utility.todo.todo"),path:"UtilityTodoList"}]},{default:p(()=>[a(n,{name:"UtilityTodo",title:s.$trans("utility.todo.todo"),actions:["list"]},null,8,["title"])]),_:1},8,["title","navs"]),a(i,{appear:"",visibility:!0},{default:p(()=>[a(H)]),_:1})],64)}}});export{I as default};